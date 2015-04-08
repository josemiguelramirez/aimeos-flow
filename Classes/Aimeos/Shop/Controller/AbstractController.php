<?php

/**
 * @license LGPLv3, http://www.gnu.org/copyleft/lgpl.html
 * @copyright Aimeos (aimeos.org), 2014
 */


namespace Aimeos\Shop\Controller;

use TYPO3\Flow\Annotations as Flow;


/**
 * Abstract class with common functionality for all controllers.
 */
abstract class AbstractController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
	/**
	 * @var \Aimeos\Shop\Base\Page
	 * @Flow\Inject
	 */
	protected $page;


	/**
	 * Returns the body and header output for the given page name
	 *
	 * @param string $pageName Page name as defined in the Settings.yaml file
	 */
	protected function getSections( $pageName )
	{
		return $this->page->getSections( $this->request, $pageName );
	}
}