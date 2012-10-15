<?php
namespace MultiAuth\Auth\Adapter;

use MultiAuth\Auth\Identity\Facebook as Identity;
use MultiAuth\OAuth2\Consumer;
use \Zend_Auth_Result as Result;
use \Zend_Registry as Registry;

/**
 * Facebook adapter to OAuth authentication
 *
 * @package     MultiAuth
 * @subpackage  Adapter
 * @author      Roel Obdam
 * @author      Darlan Alves
 */
class Facebook implements AdapterInterface {
    
    protected $accessToken;
    protected $requestToken;
    protected $options;

    /**
     * Constructor.
     *
     * @param string $requestToken
     * @param array $options
     * @return $this
     */
    public function __construct($requestToken = null, $options) {
        $this->options = $options;
        $this->setRequestToken($requestToken);
        return $this;
    }

    /**
     * Perform the authentication
     *
     * @return \Zend_Auth_Result
     */
    public function authenticate() {
        $result = array();
        $result['code'] = Result::FAILURE;
        $result['identity'] = null;
        $result['messages'] = array();
        
        $identity = new Identity($this->accessToken);
        if (null !== $identity->getId()) {
            $result['code'] = Result::SUCCESS;
            $result['identity'] = $identity;
        }

        return new Result($result['code'], $result['identity'], $result['messages']);
    }

    /**
     * Get a valid authorization URL from API
     *
     * @param array adapter options
     * @return string URL
     */
    public static function getAuthorizationUrl($options) {
        return Consumer::getAuthorizationUrl($options);
    }

    /**
     * Set the requestToken
     *
     * @param string $requestToken
     * @return $this
     */
    protected function setRequestToken($requestToken) {
        $this->options['code'] = $requestToken;
        
        // catch errors while accessing the API (e.g. no internet access)
        $accessToken = Consumer::getAccessToken($this->options);
        $accessToken['timestamp'] = time();
        $this->accessToken = $accessToken;
        
        return $this;
    }
    
    /**
     * Set the accessToken
     *
     * @param string $token
     * @return $this
     */
    public function setAccessToken($token) {
        $accessToken = array();
        $accessToken['timestamp'] = time();
        $accessToken['access_token'] = $token;
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Set adapter parameters
     *
     * @param array $options
     * @return $this
     */
    protected function setOptions($options = null) {
        $this->options = $options;
    }
}
