<?php
namespace MultiAuth;

use MultiAuth\Provider;
use MultiAuth\Auth\Storage\MultipleIdentities;
use MultiAuth\Auth\Identity\Container;
use MultiAuth\Auth\Identity\Generic as GenericIdentity;
/**
 * Abstraction to user login process
 * 
 * This class is almost an identical copy of the Zend_Auth class.
 * Their are a few things different which are commented on.
 * 
 * @package     MultiAuth
 * @subpackage  Auth
 * @author      Roel Obdam
 * @author      Darlan Alves
 *
 */
class Auth {
    
    /**
     * Singleton instance
     * @var MultiAuth\Auth
     */
    protected static $instance = null;
    
    /**
     * Instance of \Zend_Auth_Storage_Interface
     * 
     * @var MultiAuth\Auth\Storage\MultipleIdentities
     */
    protected $storage = null;
    
    /**
     * @var MultiAuth\Provider
     */
    protected $provider;
    
    /**
     * Singleton pattern
     * @return MultiAuth\Auth;
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }
    
    /**
     * Constructor - Use Auth::getInstance instead!
     * @return void
     */
    public function __construct() {
        $request = \Zend_Controller_Front::getInstance()->getRequest();
        $this->provider = new Provider($request);
    }
    
    /**
     * @ignore
     */
    protected function __clone() {
        // Singleton pattern
    }
    
    /**
     * Set identities storage
     * 
     * @param \Zend_Auth_Storage_Interface $storage
     * @return $this
     */
    public function setStorage(\Zend_Auth_Storage_Interface $storage) {
        $this->storage = $storage;
        return $this;
    }
 
    /**
     * Get identities storage. The default storage is the MultipleIdenties class
     *
     * @param \Zend_Auth_Storage_Interface $storage
     */
    public function getStorage() {
        if (null === $this->storage) {
            $this->setStorage(new MultipleIdentities());
        }
    
        return $this->storage;
    }
    
    /**
     * Get identities storage. The default storage is the MultipleIdenties class
     *
     * @param \Zend_Auth_Storage_Interface $storage
     */
    public function getProvider() {
        return $this->provider;
    }
    
    /**
     * Get the API authorization URL provided by the adapter
     */
    public function getAuthorizationUrl($adapterName) {
        return $this->provider->getAuthorizationUrl($adapterName);
    }
    
    /**
     * This function doesn't delete the identity information but adds the new 
     * identity to the storage. This function only works with adapters that 
     * create a Generic identity.
     * 
     * @param string adapter to use. One of:
     * - Provider::ADAPTER_FACEBOOK
     * - Provider::ADAPTER_GOOGLE
     * - Provider::ADAPTER_TWITTER
     * - Provider::ADAPTER_BASIC
     * 
     * @throws Exception
     */
    public function authenticate($adapterName) {
        $result = $this->provider->authenticate($adapterName);
        
        $identity = $result->getIdentity();
        
        if(null === $identity) {
            return $result;
        }
        
        if (!$identity instanceof GenericIdentity) {
            throw new \Exception('Not a valid identity');
        }
    
        $currentIdentity = $this->getIdentity();
    
        if(false === $currentIdentity || get_class($currentIdentity) !== 'MultiAuth\Auth\Identity\Container') {
            $currentIdentity = new Container();
        }
        
        $currentIdentity->add($result->getIdentity());
    
        if ($this->hasIdentity()) {
           $this->clearIdentity();
        }
    
        if ($result->isValid()) {
           $this->getStorage()->write($currentIdentity);
        }
    
        return $result;
    }
 
    /**
     * Return true if the container has a identity
     * 
     * @param $provider
     */
    public function hasIdentity($provider = null) {
        return !$this->getStorage()->isEmpty($provider);
    }
    
    /**
     * Get a identity
     *
     * @param string provider
     */
    public function getIdentity($provider = null) {
        $storage = $this->getStorage();
        
        if ($storage->isEmpty($provider)) {
            return false;
        }
        
        return $storage->read($provider);
    }
    
    /**
     * Remove a identity from storage
     *
     * @param $provider
     */
    public function clearIdentity($provider = null) {
        $this->getStorage()->clear($provider);
    }
    
}
