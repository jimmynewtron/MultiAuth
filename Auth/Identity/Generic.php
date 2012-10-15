<?php
namespace MultiAuth\Auth\Identity;

/**
 * Base class to identities
 *
 * @package     MultiAuth
 * @subpackage  Identity
 * @author      Roel Obdam
 * @author      Darlan Alves
 * @abstract
 */
abstract class Generic {
    
	protected $id;
	protected $name;
    protected $api;

	public function __construct() {
		//$this->name = $name;
		//$this->id = $id;
	}

    /**
     * Get identity name (google, facebook, twitter or basic)
     */
	public function getName() {
		return $this->name;
	}

	public function getId() {
		return $this->id;
	}
    
    /**
     * Get interface to identity (Resource)
     */
    public function getApi() {
		return $this->api;
	}
}
