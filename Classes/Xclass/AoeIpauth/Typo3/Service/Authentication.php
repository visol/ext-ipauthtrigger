<?php

namespace Visol\Ipauthtrigger\Xclass\AoeIpauth\Typo3\Service;

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

/**
 * Class Authentication
 *
 * @package AOE\AoeIpauth\Typo3\Service
 */
class Authentication extends \AOE\AoeIpauth\Typo3\Service\Authentication
{

    /**
     * Gets the user automatically
     *
     * @return bool
     */
    public function getUser()
    {
        // Do not respond to non-fe users and login attempts
        if ('getUserFE' != $this->mode /*|| 'login' == $this->login['status']*/) {
            return false;
        }

        $this->safeguardContext();

        $clientIp = $this->authInfo['REMOTE_ADDR'];
        $ipAuthenticatedUsers = $this->findAllUsersByIpAuthentication($clientIp);

        if (empty($ipAuthenticatedUsers)) {
            return false;
        }

        $user = array_pop($ipAuthenticatedUsers);
        return $user;
    }

    /**
     * Authenticate a user
     * Return 200 if the IP is right.
     * This means that no more checks are needed.
     * Otherwise authentication may fail because we may don't have a password.
     *
     * @param array $user Data of user.
     *
     * @return bool
     */
    public function authUser($user)
    {

        $this->safeguardContext();

        $authCode = 100;

        // Do not respond to non-fe users and login attempts
        if ('FE' != $this->authInfo['loginType'] /*|| 'login' == $this->login['status']*/) {
            return $authCode;
        }
        if (!isset($user['uid'])) {
            return $authCode;
        }

        $clientIp = $this->authInfo['REMOTE_ADDR'];
        $userId = $user['uid'];

        $ipMatches = $this->doesCurrentUsersIpMatch($userId, $clientIp);

        if ($ipMatches) {
            $authCode = 200;
        }

        return $authCode;
    }
}
