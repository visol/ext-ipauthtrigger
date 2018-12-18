<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    $_EXTKEY,
    'Ipauthtrigger',
    'IP Authentication Trigger'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['ipauthtrigger_ipauthtrigger'] = 'recursive,select_key,pages';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    $_EXTKEY,
    'Configuration/TypoScript',
    'IP Authentication Trigger'
);
