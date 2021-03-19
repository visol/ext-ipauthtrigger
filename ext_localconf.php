<?php

defined('TYPO3') || die();

(function ($extKey = 'ipauthtrigger') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Visol.' . $extKey,
        'Ipauthtrigger',
        [
            'Authentication' => 'index',

        ],
        // non-cacheable actions
        [
            'Authentication' => 'index',
        ]
    );

    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_ipauthtrigger_async'] = \Visol\Ipauthtrigger\Eid\AsyncTrigger::class . '::main';

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\AOE\AoeIpauth\Typo3\Service\Authentication::class] = [
        'className' => \Visol\Ipauthtrigger\Xclass\AoeIpauth\Typo3\Service\Authentication::class
    ];
})();


