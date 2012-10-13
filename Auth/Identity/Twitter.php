<?php
namespace MultiAuth\Auth\Identity;

use \MultiAuth\Resource\Twitter as Resource;

class Twitter extends Generic
{
	protected $_api;

	public function __construct($token, $options)
	{
        if (!$token) {
            throw new \Exception('Invalid auth token!');
        }
		$this->_api = new Resource($token, $options);
		$this->_name = 'twitter';
		$this->_id = $this->_api->getId();
	}

	public function getApi()
	{
		return $this->_api;
	}
}
