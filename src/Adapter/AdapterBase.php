<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

namespace Hybridauth\Adapter;

use Hybridauth\Exception;
use Hybridauth\Logger;
use Hybridauth\Data;
use Hybridauth\Storage\StorageInterface;
use Hybridauth\Storage\Session;
use Hybridauth\HttpClient;
use Hybridauth\HttpClient\HttpClientInterface;

use Hybridauth\Deprecated\DeprecatedAdapterTrait;

/**
 *
 */
abstract class AdapterBase implements AdapterInterface 
{
	use AdapterTokensTrait, DeprecatedAdapterTrait, AdapterHelperTrait;

	/**
	* Provider ID (unique name)
	*
	* @var string
	*/
	protected $providerId = '';

	/**
	* Specific Provider config
	*
	* @var mixed
	*/
	protected $config = array();

	/**
	* Extra Provider parameters
	*
	* @var mixed
	*/
	protected $params = array();

	/**
	* Redirection Endpoint (i.e., redirect_uri, callback_url)
	*
	* @var string
	*/
	protected $endpoint = ''; 

	/**
	* Storage
	*
	* @var object
	*/
	public $storage = null;

	/**
	* HttpClient
	*
	* @var object
	*/
	public $httpClient = null;

	/**
	* Logger
	*
	* @var object
	*/
	public $logger = null;

	/**
	* Common adapters constructor
	*
	* @param string $providerId
	* @param array  $config
	* @param object $httpClient
	* @param object $storage
	* @param object $logger
	*/
	function __construct( $config = array(), HttpClientInterface $httpClient = null, StorageInterface $storage = null, $logger = null )
	{
		$this->providerId = str_replace( 'Hybridauth\\Provider\\', '', get_class($this) ); 

		$this->httpClient = $httpClient ? $httpClient : new HttpClient\Curl();
		$this->storage    = $storage ? $storage : new Session();
		$this->logger     = $logger ? $logger : new Logger( 
			( isset( $config['debug_mode'] ) ? $config['debug_mode'] : false ), 
			( isset( $config['debug_file'] ) ? $config['debug_file'] : '' ) 
		);

		$this->logger->info( 'Initialize ' . get_class($this) . '. Dump provider config: ', $config );

		$this->config = new Data\Collection( $config );

		$this->endpoint = $this->config->get( 'callback' );

		$this->initialize();
	}

	/**
	* Adapter initializer
	*/
	abstract protected function initialize(); 

	/**
	* {@inheritdoc}
	*/
	function getUserContacts()
	{
		throw new Exception( 'Provider does not support this feature.', 8 ); 
	}

	/**
	* {@inheritdoc}
	*/
	function setUserStatus( $status )
	{
		throw new Exception( 'Provider does not support this feature.', 8 ); 
	}

	/**
	* {@inheritdoc}
	*/
	function getUserActivity( $stream )
	{
		throw new Exception( 'Provider does not support this feature.', 8 ); 
	}

	/**
	* Return oauth access tokens
	*
	* @param array $tokensNames
	*
	* @return array
	*/
	function getAccessToken( $tokenNames = array() )
	{
		throw new Exception( 'Provider does not support this feature.', 8 ); 
	}
}
