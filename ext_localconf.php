<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Visol.' . $_EXTKEY,
    'Ipauthtrigger',
    [
        'Authentication' => 'index',

    ],
    // non-cacheable actions
    [
        'Authentication' => 'index',
    ]
);

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_ipauthtrigger_async'] = 'EXT:ipauthtrigger/Classes/Eid/AsyncTrigger.php';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['AOE\\AoeIpauth\\Typo3\\Service\\Authentication'] = [
    'className' => \Visol\Ipauthtrigger\Xclass\AoeIpauth\Typo3\Service\Authentication::class
];
