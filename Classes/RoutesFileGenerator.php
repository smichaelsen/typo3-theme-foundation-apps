<?php
namespace AppZap\ThemeFoundationApps;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class RoutesFileGenerator {

	const APP_TEMPLATES_FOLDER = '%sResources/Private/App/client/templates';

	/**
	 * @param string $appExtensionKey
	 * @return string
	 * @throws \Exception
	 */
	public function ensureRoutesFileExists($appExtensionKey) {
		$routesFile = ExtensionManagementUtility::extPath($appExtensionKey) . 'Resources/Public/js/routes.js';
		if (!file_exists($routesFile)) {
			$this->writeRoutesFile($routesFile, $this->getRoutesData($appExtensionKey));
		}
	}

	/**
	 * @param $appExtensionKey
	 * @return array
	 * @throws \Exception
	 */
	protected function getRoutesData($appExtensionKey) {
		$routesData = [];
		$appTemplatesPath = sprintf(
			self::APP_TEMPLATES_FOLDER,
			ExtensionManagementUtility::extPath($appExtensionKey)
		);
		if (!is_dir($appTemplatesPath)) {
			throw new \Exception('Routes for extension ' . $appExtensionKey . ' couldn\'t be loaded. Template folder not found', 1437032244);
		}
		$directory = new \DirectoryIterator($appTemplatesPath);
		foreach ($directory as $file) {
			/** @var \DirectoryIterator $file */
			if ($file->getExtension() === 'html') {
				$templateFileData = $this->parseTemplateFile($file->getPathname());
				$routesData[$templateFileData['name']] = [
					'path' => $this->getTemplateRetreivalUrl($file->getFilename(), $appExtensionKey),
					'url' => $templateFileData['url'],
				];
			}
		}
		return $routesData;
	}

	/**
	 * @param string $file
	 * @return array
	 */
	protected function parseTemplateFile($file) {
		$templateFileData = [];
		$templateFileArray = file($file);
		$inHeader = FALSE;
		foreach ($templateFileArray as $line) {
			if (trim($line) === '---') {
				if ($inHeader) {
					break;
				} else {
					$inHeader = TRUE;
					continue;
				}
			}
			if ($inHeader) {
				list($key, $value) = GeneralUtility::trimExplode(':', $line, FALSE, 2);
				$templateFileData[$key] = $value;
			}
		}
		return $templateFileData;
	}

	/**
	 * @param string $routesFile
	 * @param array $routesData
	 */
	protected function writeRoutesFile($routesFile, $routesData) {
		$pathDataStrings = [];
		foreach ($routesData as $name => $pathData) {
			$assignmentStrings = ['"name":"' . $name . '"'];
			foreach ($pathData as $key => $value) {
				$assignmentStrings[] = '"' . $key . '":"' . $value . '"';
			}
			$pathDataStrings[] = '{' . join(',', $assignmentStrings) . '}';
		}
		$content = 'var foundationRoutes = [' . join(',', $pathDataStrings) . '];';
		$fh = fopen($routesFile, 'w');
		fwrite($fh, $content);
		fclose($fh);
	}

	/**
	 * @param string $filename
	 * @param string $appExtensionKey
	 * @return string
	 */
	protected function getTemplateRetreivalUrl($filename, $appExtensionKey) {
		return $this->getTyposcriptFrontendController()->cObj->typoLink_URL([
			'parameter' => $this->getTyposcriptFrontendController()->id . ',0',
			'additionalParams' => '&eID=Tx_ThemeFoundationApps_LoadTemplate&templateFilename=' . urlencode($filename) . '&appExtensionKey=' . urlencode($appExtensionKey),
			'forceAbsoluteUrl' => TRUE,
		]);
	}

	/**
	 * @return TypoScriptFrontendController
	 */
	protected function getTyposcriptFrontendController() {
		return $GLOBALS['TSFE'];
	}

}
