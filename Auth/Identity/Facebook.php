<?php
namespace MultiAuth\Auth\Identity;

use \MultiAuth\Resource\Facebook as Resource;

class Facebook extends Generic {
    
    /**
     * Instance of Resource
     * @var \MultiAuth\Resource\Facebook
     */
	protected $api;

	public function __construct($token) {
		$this->api = new Resource($token);
		$this->name = 'facebook';
		$this->id = $this->api->getId();
    }
}
