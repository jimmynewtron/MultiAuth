<?php
namespace MultiAuth\Auth\Adapter;
use MultiAuth\Auth\Identity\Basic as Identity;
use MultiAuth\OAuth2\Consumer;
use MultiAuth\Auth\Exception\BasicAuthException;
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
class Basic implements AdapterInterface {
    
    #const FAILURE_NOT_ACTIVATED = -50;
	
    /**
     * Credentials
     */
    private $identity;
    private $credential;

    /**
     * Constructor.
     * 
     * @param array $options (profileService, profileEntity)
     */
    function __construct($options) {
        $this->options = $options;
        $this->request = \Zend_Controller_Front::getInstance()->getRequest();
    }
    
    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        $providerClass = $this->options['providerClass'];
        
        if (!class_exists($providerClass, true)) {
            throw new \Exception('Invalid authentication class: ' . $providerClass);
        }
        
        $provider = new $providerClass();
        
        if (!method_exists($provider, 'authenticate')) {
            throw new \Exception('The authentication provider has no method called "authenticate"');
        }
        
        $params = $this->loadParams();
        $profile = $provider->authenticate($params['identity'], $params['credential']);

        $result = array();
        $result['code'] = Result::FAILURE;
        $result['identity'] = null;
        $result['messages'] = array();
        
        if ($profile) {
            $result['code'] = Result::SUCCESS;
            $result['identity'] = new Identity($profile);
        }
        
        return new Result($result['code'], $result['identity'], $result['messages']);
    }
    
    /**
     * Set adapter params
     * 
     * @param array(identity, password)
     * @return $this
     */
    public function loadParams() {
        $identityKey = $this->options['identityKey'];
        $credentialKey = $this->options['credentialKey'];
        
        $identity = trim($this->request->getParam($identityKey));
        $credential = trim($this->request->getParam($credentialKey));
        
        if (!$identity) {
            throw new BasicAuthException('Invalid identity provided!');
        } elseif (!$credential) {
            throw new BasicAuthException('Invalid credential provided!');
        } else {
            return array(
                'identity' => $identity,
                'credential' => $credential
            );
        }
    }
    
    /**
     * Interface method - noop
     */
    public static function getAuthorizationUrl($options) {}

}