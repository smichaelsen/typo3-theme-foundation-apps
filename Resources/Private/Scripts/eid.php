<?php

/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

/** @var \Stocked\Stocked\Controller\AjaxTemplateLoadingController $templateLoadingController */
$templateLoadingController = $objectManager->get(\Stocked\Stocked\Controller\AjaxTemplateLoadingController::class);
echo $templateLoadingController->loadTemplateAction($_GET['templateFilename'], $_GET['appExtensionKey']);
