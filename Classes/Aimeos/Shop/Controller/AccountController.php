<?php

/**
 * @license LGPLv3, http://www.gnu.org/copyleft/lgpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package flow
 * @subpackage Controller
 */


namespace Aimeos\Shop\Controller;

use TYPO3\Flow\Annotations as Flow;


/**
 * Account controller
 * @package flow
 * @subpackage Controller
 */
class AccountController extends AbstractController
{
	/**
	 * Returns the output of the account favorite component
	 *
	 * @return string Rendered HTML for the body
	 */
	public function favoriteComponentAction()
	{
		$this->view->assign( 'output', $this->getOutput( 'account/favorite' ) );
	}


	/**
	 * Returns the output of the account history component
	 *
	 * @return string Rendered HTML for the body
	 */
	public function historyComponentAction()
	{
		$this->view->assign( 'output', $this->getOutput( 'account/history' ) );
	}


	/**
	 * Returns the output of the account watch component
	 *
	 * @return string Rendered HTML for the body
	 */
	public function watchComponentAction()
	{
		$this->view->assign( 'output', $this->getOutput( 'account/watch' ) );
	}


	/**
	 * Content for MyAccount page
	 *
	 * @Flow\Session(autoStart = TRUE)
	 */
	public function indexAction()
	{
		$this->view->assignMultiple( $this->getSections( 'account-index' ) );
	}


	/**
	 * Content for MyAccount download page
	 *
	 * @Flow\Session(autoStart = TRUE)
	 */
	public function downloadAction()
	{
		$context = $this->context->get( $this->request );
		$langid = $context->getLocale()->getLanguageId();

		$view = $this->viewContainer->create( $context->getConfig(), $this->uriBuilder, array(), $this->request, $langid );
		$context->setView( $view );

		$client = \Aimeos\Client\Html\Factory::createClient( $context, array(), 'account/download' );
		$client->setView( $view );
		$client->process();

		$response = $view->response();
		$flowResponse = \TYPO3\Flow\Http\Response::createFromRaw( (string) $response->getBody() );
		$flowResponse->setStatus( $response->getStatusCode() );

		foreach( $response->getHeaders() as $key => $value ) {
			$flowResponse->setHeader( $key, $value );
		}

		return $flowResponse;
	}
}
