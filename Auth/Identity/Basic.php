<?php
namespace MultiAuth\Auth\Identity;

class Basic extends Generic {
    
    public function __construct($profile = null) {
		$this->api = $profile;
		$this->name = 'basic';
		$this->id = null;
        
        // we expect a "getId" method within profile object
        if (method_exists($profile, 'getId')) {
            $this->id = $profile->getId();
        }
	}

	public function getApi() {
		return $this->api;
	}
    
    public function getProfile() {
        return $this->api;
    }
    
    public function getPicture() {
        if (method_exists($this->api, 'getPicture')) {
            return $this->api->getPicture();
        }
    }
    
}