<?php

namespace Visol\Ipauthtrigger\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use AOE\AoeIpauth\Domain\Service\FeEntityService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

class AuthenticationController extends ActionController
{

    /**
     * @var \AOE\AoeIpauth\Domain\Service\FeEntityService
     */
    protected $feEntityService = null;

    /**
     * @throws StopActionException
     * @return void
     */
    public function indexAction()
    {
        $loginType = GeneralUtility::_GP('logintype');
        if (!empty($loginType)) {
            throw new StopActionException;
        } else {
            $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
            $users = $this->findAllUsersByIpAuthentication($ip);
            if (count($users)) {
                $requestUrl = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
                $queryStringSeparator = !strpos($requestUrl, '?') ? '?' : '&';
                HttpUtility::redirect($requestUrl . $queryStringSeparator . 'logintype=login');
            } else {
                if ($this->settings['debugMode']) {
                    // Set plugin.tx_ipauthtrigger.settings.debugMode = 1 if you want to display
                    // an alert if IP authentication is not possible. This is a function for debugging
                    // purposes.
                    $this->view->assign('currentIp', GeneralUtility::getIndpEnv('REMOTE_ADDR'));
                    $ipUsers = $this->feEntityService->findAllUsersWithIpAuthentication();
                    $allowedIpAddresses = [];
                    foreach ($ipUsers as $ipUser) {
                        $allowedIpAddresses[] = implode(',', $ipUser['tx_aoeipauth_ip']);
                    }
                    $this->view->assign('allowedIpAddresses', implode(',', $allowedIpAddresses));
                } else {
                    throw new StopActionException;
                }
            }
        }
    }

    /**
     * Finds all users with IP authentication enabled
     *
     * @param string $ip
     *
     * @return array
     */
    protected function findAllUsersByIpAuthentication($ip)
    {
        $users = $this->getFeEntityService()->findAllUsersAuthenticatedByIp($ip);
        return $users;
    }

    /**
     * @return \AOE\AoeIpauth\Domain\Service\FeEntityService
     */
    protected function getFeEntityService()
    {
        if (null === $this->feEntityService) {
            $this->feEntityService = GeneralUtility::makeInstance(FeEntityService::class);
        }
        return $this->feEntityService;
    }
}
