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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use Visol\Ipauthtrigger\Service\AuthenticationService;

class AuthenticationController extends ActionController
{

    protected ?AuthenticationService $authenticationService = null;

    public function injectAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return void
     * @throws StopActionException
     */
    public function indexAction()
    {
        $loginType = GeneralUtility::_GP('logintype');
        if (!empty($loginType)) {
            throw new StopActionException;
        } else {
            $ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
            $users = $this->authenticationService->findAllUsersByIpAuthentication($ip);
            if (count($users)) {
                $requestUrl = GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL');
                $queryStringSeparator = !strpos($requestUrl, '?') ? '?' : '&';
                $targetUrl = $requestUrl . $queryStringSeparator . 'logintype=login';

                $configuredRedirectPage = $this->getConfiguredRedirectPage();
                if (empty($redirectUrl) && !empty($configuredRedirectPage)) {
                    $targetUrl = $this->uriBuilder->setTargetPageUid(
                        (int)$configuredRedirectPage
                    )->setCreateAbsoluteUri(true)->setLinkAccessRestrictedPages(true)->setArguments(
                        ['logintype' => 'login']
                    )->build();
                }

                HttpUtility::redirect($targetUrl);
            } else {
                if ($this->settings['debugMode']) {
                    // Set plugin.tx_ipauthtrigger.settings.debugMode = 1 if you want to display
                    // an alert if IP authentication is not possible. This is a function for debugging
                    // purposes.
                    $this->view->assign('currentIp', GeneralUtility::getIndpEnv('REMOTE_ADDR'));
                    $ipUsers = $this->authenticationService->findAllUsersWithIpAuthentication();
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

    protected function getConfiguredRedirectPage()
    {
        $configuredRedirectPage = null;
        if (array_key_exists('redirectPage', $this->settings) && !empty($this->settings['redirectPage'])) {
            $configuredRedirectPage = $this->settings['redirectPage'];
        }
        return $configuredRedirectPage;
    }

}
