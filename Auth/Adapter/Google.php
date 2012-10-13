<?php
namespace MultiAuth\Auth\Adapter;

use \MultiAuth\Auth\Identity\Google as Identity;
use \MultiAuth\OAuth2\Consumer;
use \Zend_Auth_Result as Result;

/**
 * Google adapter to OAuth authentication
 *
 * @package     MultiAuth
 * @subpackage  Adapter
 * @author      Roel Obdam
 * @author      Darlan Alves
 */
class Google implements AdapterInterface {
    
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
    public function __construct($requestToken, $options = null) {
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
        $result['identity'] = NULL;
        $result['messages'] = array();
        
        if(!array_key_exists('error',$this->accessToken)) {
            $result['code'] = Result::SUCCESS;
            $result['identity'] = new Identity($this->accessToken);
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
        try {
            $accesstoken = Consumer::getAccessToken($this->options);
            $accesstoken['timestamp'] = time();
            $this->accessToken = $accesstoken;
        } catch (\Exception $e) {}
        return $this;
    }
    
    /**
     * Set adapter parameters
     *
     * @param array $options
     * @return $this
     */
    protected function setOptions($options) {
        $this->options = $options;
        return $this;
    }
}
