<?php

namespace Visol\Ipauthtrigger\Eid;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;
use Visol\Ipauthtrigger\Service\AuthenticationService;

if (!defined('PATH_typo3conf')) {
    die('Could not access this script directly!');
}

class AsyncTrigger
{

    public function main()
    {
        $typoscriptFrontendController = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            0,
            0
        );
        $pid = GeneralUtility::_GP('id');

        $GLOBALS['TSFE'] = new $typoscriptFrontendController($GLOBALS['TYPO3_CONF_VARS'], $pid, 0, true);
        $GLOBALS['TSFE']->connectToDB();
        $GLOBALS['TSFE']->fe_user = EidUtility::initFeUser();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();

        if (isset($GLOBALS['TSFE']->fe_user->user)) {
            // Don't do anything if a user is already authenticated
            return;
        }

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $objectManager->get(AuthenticationService::class);

        $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $users = $authenticationService->findAllUsersByIpAuthentication($ip);

        if (count($users) === 0) {
            // No IP user, exit
            return;
        }

        // Serve a JavaScript that reloads the current page with ?logintype=login
        header('Content-Type: application/javascript');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        echo "
            url = location.href;
            url += (url.split('?')[1] ? '&':'?') + 'logintype=login';
            window.location.replace(url);
        ";
        exit;
    }
}

/** @var AsyncTrigger $asyncTriggerInstance */
$asyncTriggerInstance = GeneralUtility::makeInstance(AsyncTrigger::class);
$asyncTriggerInstance->main();
