<?php

/**
 * @license LGPLv3, http://www.gnu.org/copyleft/lgpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package flow
 * @subpackage Base
 */


namespace Aimeos\Shop\Base;

use TYPO3\Flow\Annotations as Flow;


/**
 * Class providing the page objects
 *
 * @package flow
 * @subpackage Base
 * @Flow\Scope("singleton")
 */
class Page
{
	/**
	 * @var \Aimeos\Shop\Base\Aimeos
	 * @Flow\Inject
	 */
	protected $aimeos;

	/**
	 * @var \Aimeos\Shop\Base\Context
	 * @Flow\Inject
	 */
	protected $context;

	/**
	 * @var \Aimeos\Shop\Base\View
	 * @Flow\Inject
	 */
	protected $view;

	/**
	 * @var \TYPO3\Flow\Mvc\Routing\UriBuilder
	 * @Flow\Inject
	 */
	protected $uriBuilder;

	/**
	 * @var array
	 */
	private $settings;


	/**
	 * Returns the body and header sections created by the clients configured for the given page name.
	 *
	 * @param \TYPO3\Flow\Mvc\RequestInterface $request Request object
	 * @param string $pageName Name of the configured page
	 * @return array Associative list with body and header output separated by client name
	 */
	public function getSections( \TYPO3\Flow\Mvc\RequestInterface $request, $pageName )
	{
		$this->uriBuilder->setRequest( $request );

		$tmplPaths = $this->aimeos->get()->getCustomPaths( 'client/html/templates' );
		$pagesConfig = $this->settings['page'];
		$result = array( 'aibody' => array(), 'aiheader' => array() );

		$context = $this->context->get( $request );
		$langid = $context->getLocale()->getLanguageId();
		$view = $this->view->create( $context, $this->uriBuilder, $tmplPaths, $request, $langid );
		$context->setView( $view );

		if( isset( $pagesConfig[$pageName] ) )
		{
			foreach( (array) $pagesConfig[$pageName] as $clientName )
			{
				$client = \Aimeos\Client\Html\Factory::createClient( $context, $tmplPaths, $clientName );
				$client->setView( clone $view );
				$client->process();

				$varName = str_replace( '/', '_', $clientName );

				$result['aibody'][$varName] = $client->getBody();
				$result['aiheader'][$varName] = $client->getHeader();
			}
		}

		return $result;
	}


	/**
	 * Inject the settings
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings( array $settings )
	{
		$this->settings = $settings;
	}
}
