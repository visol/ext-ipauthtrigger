<?php

namespace Visol\Ipauthtrigger\Service;

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
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationService implements SingletonInterface
{

    /**
     * @var \AOE\AoeIpauth\Domain\Service\FeEntityService
     */
    protected $feEntityService = null;

    /**
     * Finds all users with IP authentication enabled
     *
     * @param string $ip
     *
     * @return array
     */
    public function findAllUsersByIpAuthentication($ip)
    {
        return $this->getFeEntityService()->findAllUsersAuthenticatedByIp($ip);
    }

    /**
     * @return array
     */
    public function findAllUsersWithIpAuthentication() {
        return $this->getFeEntityService()->findAllGroupsWithIpAuthentication();
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
