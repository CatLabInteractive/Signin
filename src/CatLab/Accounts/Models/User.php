<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 30/11/14
 * Time: 15:23
 */

namespace CatLab\Accounts\Models;

use CatLab\Accounts\MapperFactory;
use Neuron\Collections\Collection;

class User
	implements \Neuron\Interfaces\Models\User
{

	/** @var int $id */
	private $id;

	/** @var string $email */
	private $email;

	/** @var string $password */
	private $password;

	/** @var string $passwordhash */
	private $passwordhash;

	/** @var string $username */
	private $username;

	public function __construct ()
	{

	}

	/**
	 * @return int
	 */
	public function getId ()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId ($id)
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getEmail ()
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 */
	public function setEmail ($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getPassword ()
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword ($password)
	{
		$this->password = $password;
	}

	/**
	 * @param string $hash
	 */
	public function setPasswordHash ($hash)
	{
		$this->passwordhash = $hash;
	}

	/**
	 * @return string
	 */
	public function getPasswordHash ()
	{
		return $this->passwordhash;
	}

	/**
	 * @return string
	 */
	public function getUsername ()
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername ($username)
	{
		$this->username = $username;
	}

	/**
	 * @param null $type The type of user we are requesting.
	 * @return Collection
	 */
	public function getDeligatedAccounts ($type = null) {
		return MapperFactory::getDeligatedMapper ()->getFromUser ($this, $type);
	}
}