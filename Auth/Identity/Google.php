<?php
namespace MultiAuth\Auth\Identity;

use \MultiAuth\Resource\Google as Resource;

class Google extends Generic {
    
    /**
     * Instance of Resource
     * @var \MultiAuth\Resource\Google
     */
    protected $api;
    
    public function __construct($token) {
        $this->api = new Resource($token);
        $this->name = 'google';
        $this->id = $this->api->getId();
    }
    
    public function getApi() {
        return $this->api;
    }
}
