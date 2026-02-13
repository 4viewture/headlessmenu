<?php

if (!defined('TYPO3')) {
    die('Access denied.');
}

(function() {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
        'headlessmenu',
        'Configuration/TypoScript',
        '4viewture Headlessmenu endpoint'
    );
})();
