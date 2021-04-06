<?php

defined('TYPO3') || die();

(function ($extKey = 'ipauthtrigger') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'ipauthtrigger',
        'Ipauthtrigger',
        'IP Authentication Trigger'
    );
    $pluginSignature = str_replace('_', '', $extKey) . '_ipauthtrigger';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages, recursive';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginSignature,
        'FILE:EXT:' . $extKey . '/Configuration/FlexForm/flexform_authentication.xml'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        $extKey,
        'Configuration/TypoScript',
        'IP Authentication Trigger'
    );
})();



