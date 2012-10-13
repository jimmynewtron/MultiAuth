<?php
namespace MultiAuth;

use MultiAuth\Auth\Adapter\Facebook;
use MultiAuth\Auth\Adapter\Google;
use MultiAuth\Auth\Adapter\Twitter;
use MultiAuth\Auth\Adapter\Basic;

use MultiAuth\Auth\Exception\GoogleAuthException;
use MultiAuth\Auth\Exception\FacebookAuthException;
use MultiAuth\Auth\Exception\TwitterAuthException;
use MultiAuth\Auth\Exception\BasicAuthException;

/**
 * Generic implementation of multiple login methods.
 *
 * This class should be extended and modified only if really needed.
 * Currently, we handle the login process using Google, Facebook, Twitter and Basic()
 * 
 * @package     MultiAuth
 * @author      Darlan Alves
 * @throws      MultiAuth\Auth\Exception\TwitterAuthException
 * @throws      MultiAuth\Auth\Exception\FacebookAuthException
 * @throws  MultiAuth\Auth\Exception\GoogleAuthException
 */
class Provider {
    
    const ADAPTER_FACEBOOK = 'facebook';
    const ADAPTER_GOOGLE = 'google';
    const ADAPTER_TWITTER = 'twitter';
    const ADAPTER_BASIC = 'basic';
    
    /**
     * Auth options (auth.ini)
     *
     * @var array
     * @ignore
     */
    protected $options;
    
    /**
     * @var \Zend_Controller_Request_Abstract
     */
    protected $request;
    
    /**
     * Stack of adapters to auth
     * @todo provide a method to push custom adapters
     */
    //protected $adapters = array();
    
    /**
     * Constructor.
     *
     * @param \Zend_Controller_Request_Abstract $request
     * @return $this
     */
    public function __construct(\Zend_Controller_Request_Abstract $request) {
        $this->request = $request;
        return $this;
    }
    
    /**
     * Get a valid authorization URL to the OAuth process
     * 
     * @param string adapter to use, one of ADAPTER_FACEBOOK, ADAPTER_GOOGLE, ADAPTER_TWITTER
     */
    public function getAuthorizationUrl($adapterName) {
        switch ($adapterName) {
            case self::ADAPTER_FACEBOOK:
                return Facebook::getAuthorizationUrl($this->getOptions(self::ADAPTER_FACEBOOK));
                break;
                
            case self::ADAPTER_GOOGLE:
                return Google::getAuthorizationUrl($this->getOptions(self::ADAPTER_GOOGLE));
                break;
                
            case self::ADAPTER_TWITTER:
                return Twitter::getAuthorizationUrl($this->getOptions(self::ADAPTER_TWITTER));
                break;
        }
    }
    
    /**
     * Authenticate using one of available adapters
     *
     * @param string adapter to use, one of ADAPTER_FACEBOOK, ADAPTER_GOOGLE, ADAPTER_TWITTER
     * @return \Zend_Auth_Result
     * @throws Exception
     */
    public function authenticate($adapterName) {
        switch ($adapterName) {
            case self::ADAPTER_BASIC:
                try {
                    return $this->getAdapter(self::ADAPTER_BASIC)->authenticate();
                } catch (\Exception $e) {
                    throw new BasicAuthException($e->getMessage());
                }
                
                break;
                
            case self::ADAPTER_FACEBOOK:
                $error = $this->request->getParam('error');
                if ($error) {
                    throw new FacebookAuthException($error);
                }
                
                try {
                    return $this->getAdapter(self::ADAPTER_FACEBOOK)->authenticate();
                } catch (\Exception $e) {
                    throw new FacebookAuthException($e->getMessage());
                }
                
                break;
                
            case self::ADAPTER_GOOGLE:
                $error = $this->request->getParam('error');
                if ($error) {
                    throw new GoogleAuthException($error);
                }
                
                $code = $this->request->getParam('code');
                if ($code) {
                    try {
                        return $this->getAdapter(self::ADAPTER_GOOGLE)->authenticate();
                    } catch (\Exception $e) {
                        throw new GoogleAuthException($e->getMessage());
                    }
                }
                
                break;
                
            case self::ADAPTER_TWITTER:
                $error = $this->request->getParam('error');
                
                if($error) {
                    throw new TwitterAuthException($error);
                }
                
                $token = $this->request->getParam('oauth_token');
                if ($token) {
                    try {
                        return $this->getAdapter(self::ADAPTER_TWITTER)->authenticate();
                    } catch (\Exception $e) {
                        throw new TwitterAuthException($e->getMessage());
                    }
                }
                
                break;
                
            default:
                throw new \Exception('Invalid provider');
        }
    }
    
    /**
     * Get a auth provider
     * 
     * @param string adapter name
     * @param mixed adapter parameters
     * @return \Zend_Auth_Adapter_Interface
     */
    protected function getAdapter($adapterName) {
        switch ($adapterName) {
            case self::ADAPTER_BASIC:
                return new Basic($this->getOptions(self::ADAPTER_BASIC));
                break;
                
            case self::ADAPTER_FACEBOOK:
                $code = $this->request->getParam('code');
                return new Facebook($code, $this->getOptions(self::ADAPTER_FACEBOOK));
                break;
                
            case self::ADAPTER_GOOGLE:
                $code = $this->request->getParam('code');
                return new Google($code, $this->getOptions(self::ADAPTER_GOOGLE));
                break;
                
            case self::ADAPTER_TWITTER:
                $params = $this->request->getParams();
                return new Twitter($params, $this->getOptions(self::ADAPTER_TWITTER));
                break;
        }
    }
    
    /**
     * Get .ini options for an adapter
     * 
     * @param string Adapter name
     * @return array
     */
    protected function getOptions($adapter) {
        if (null === $this->options) {
            $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');
            $options = $bootstrap->getApplication()->getOptions();
            
            $this->options = isset($options['auth']) ? $options['auth'] : array();
        }
        
        $options = isset($this->options[$adapter]) ? $this->options[$adapter] : array();
        
        // special case: join google scopes array
        if ($adapter == self::ADAPTER_GOOGLE && isset($options['scope']) && is_array($options['scope'])) {
            $options['scope'] = implode(' ', $options['scope']);
        }
        
        return $options;
    }
    
}