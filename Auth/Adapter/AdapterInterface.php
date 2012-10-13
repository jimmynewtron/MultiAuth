<?php
namespace MultiAuth\Auth\Adapter;

/**
 * Extension to Zend_Auth_Adapter_Interface
 *
 * Adds some extra methods and specifications
 *
 * @package     MultiAuth
 * @subpackage  Adapter
 * @author      Darlan Alves
 */
interface AdapterInterface extends \Zend_Auth_Adapter_Interface {
    
    /**
     * Returns a valid auth url (OAuth adapter)
     * 
     * @param array adapter options
     */
    public static function getAuthorizationUrl($options);
    
}