<?php

namespace TwitterFox\Signature;

use TwitterFox\Signature\Extendables\Signature;


/**
 * This class is an abstract class that handles
 */
class HmacSha1 extends Signature
{


	/**
	* Signature method
	*
	* @since 1.0
	* @var string
	*/
  protected $method = "HMAC_SHA1";


	/**
	* Signature method
	*
	* @since 1.0
	* @var string
	*/
  private $algo = "sha1";


	/**
	* Data to be signed
	*
	* @since 1.0
	* @var string
	*/
  protected $data = null;


	/**
	* Encryption Key
	*
	* @since 1.0
	* @var string
	*/
  protected $key = null;


	/**
	* Encrypted Signature
	*
	* @since 1.0
	* @var string
	*/
  protected $signature = null;


	/**
	* if this objected as been signed
	*
	* @since 1.0
	* @var string
	*/
  protected $signed = false;



  /**
   * The initial method
   *
   * @since 1.0
   *
   *
   * @param string $method path to end point
   * @param string $data http method to use for request
   * @param array $key data to send with request
   *
   */
  function __construct( string $method, string $data, string $key )
  {
      $this->method = $method;
      $this->data = $data;
      $this->key = $key;
  }



  /**
   * Sign the signature object
   *
   * @since 1.0
   *
   *
   * @return self $this
   *
   */
   public function sign() : self
   {

     if ($this->is_signed()){
       return $this;
     }

     $signature = base64_encode(hash_hmac($this->algo, $this->getData(), $this->getKey(), true));

     $this->setSignature( $signature );

     $this->signed = true;

    	return $this;

   }



  /**
   * Get the Signature key
   *
   * @since 1.0
   *
   *
   * @return string self::$key
   *
   */
  public function getKey() : string
  {
    return $this->key;
  }



 /**
  * Get the Signature key
  *
  * @since 1.0
  *
  *
  * @return bool
  *
  */
 public function is_signed() : bool
 {
   return ($this->signed) ? true : false;
 }



  /**
   * Get the Signature string
   *
   * @since 1.0
   *
   *
   * @return string self::$Signature
   *
   */
  abstract function getSignature() : string
  {
    return $this->signature;
  }



  /**
   * Get the Signature data
   *
   * @since 1.0
   *
   *
   * @return string self::$Data
   *
   */
  abstract function getData() : string
  {
    return $this->data;
  }



  /**
   * Get the Signature method
   *
   * @since 1.0
   *
   *
   * @return string self::$method
   *
   */
  abstract function getMethod() : string
  {
    return $this->method;
  }


}
