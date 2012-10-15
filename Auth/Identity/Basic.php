<?php
namespace MultiAuth\Auth\Identity;

use \MultiAuth\Resource\Basic as Resource;

/**
 * Basic auth identity
 */
class Basic extends Generic {
    
    /**
     * The object returned from adapter
     * 
     * @see MultiAuth\Auth\Adapter\Basic::authenticate
     * @var mixed
     */
    protected $api;
    
    public function __construct($profile = null) {
		$this->api = new Resource($profile);
		$this->name = 'basic';
        $this->id = $this->api->getId();
    }
    
}