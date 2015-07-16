<?php

\FluidTYPO3\Flux\Core::addStaticTypoScript('EXT:theme_foundation_apps/Configuration/TypoScript/');
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['Tx_ThemeFoundationApps_LoadTemplate'] = 'EXT:' . $_EXTKEY . '/Resources/Private/Scripts/eid.php';
