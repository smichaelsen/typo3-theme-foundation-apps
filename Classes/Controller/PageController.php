<?php
namespace AppZap\ThemeFoundationApps\Controller;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class PageController extends \FluidTYPO3\Fluidpages\Controller\PageController {

	/**
	 * @var \AppZap\ThemeFoundationApps\RoutesFileGenerator
	 * @inject
	 */
	protected $routesFileGenerator;

	public function appAction() {
		$this->routesFileGenerator->ensureRoutesFileExists($this->settings['appExtensionKey']);
	}

}
