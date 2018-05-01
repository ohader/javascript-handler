<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typolinkLinkHandler']['javascript'] = 'FoT3\\JavascriptHandler\\Hooks\\JavaScriptLinkHandlerHook';