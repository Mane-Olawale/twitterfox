<?php

namespace TwitterFox\Signature\Extendables;


/**
 * This class is an abstract class that handles
 */
abstract class Signature
{


	/**
	* Signature method
	*
	* @since 1.0
	* @var string
	*/
  abstract protected $method;


	/**
	* Data to be signed
	*
	* @since 1.0
	* @var string
	*/
  abstract protected $data;


	/**
	* Encryption Key
	*
	* @since 1.0
	* @var string
	*/
  abstract protected $key;


	/**
	* Encrypted Signature
	*
	* @since 1.0
	* @var string
	*/
  abstract protected $signature;



  /**
   * Sign the signature object
   *
   * @since 1.0
   *
   *
   * @return self $this
   *
   */
  abstract public function sign() : string;



  /**
   * Get the Signature key
   *
   * @since 1.0
   *
   *
   * @return string self::$key
   *
   */
  abstract public function getKey() : string;



  /**
   * Get the Signature string
   *
   * @since 1.0
   *
   *
   * @return string self::$Signature
   *
   */
  abstract public function getSignature() : string;



  /**
   * Get the Signature data
   *
   * @since 1.0
   *
   *
   * @return string self::$Data
   *
   */
  abstract public function getData() : string;



  /**
   * Get the Signature method
   *
   * @since 1.0
   *
   *
   * @return string self::$method
   *
   */
  abstract public function getMethod() : string;


}
