<?php
namespace MultiAuth\Auth\Identity;

use \MultiAuth\Resource\Twitter as Resource;

class Twitter extends Generic {
    
    /**
     * Instance of Resource
     * @var \MultiAuth\Resource\Twitter
     */
	protected $api;

	public function __construct($token, $options) {
        if (!$token) {
            throw new \Exception('Invalid auth token!');
        }
        
		$this->api = new Resource($token, $options);
		$this->name = 'twitter';
		$this->id = $this->api->getId();
	}
}
