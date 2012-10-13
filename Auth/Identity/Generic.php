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

	public function __construct($name, $id) {
		$this->name = $name;
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function getId() {
		return $this->id;
	}
}
