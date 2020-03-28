<?php

namespace TwitterFox\Token\Extendables;

/**
 * This class is use for holding tokens and there secret key
 */
abstract class Token
{


  /**
  * Encrypted Signature
  *
  * @since 1.0
  * @var string
  */
  protected $token = "";


  /**
  * Encrypted Signature
  *
  * @since 1.0
  * @var string
  */
  protected $secret = "";



  /**
   * The initial method
   *
   * @since 1.0
   *
   *
   * @param string $token this is the token string
   * @param string $secret this is the token secret string
   *
   */
  function __construct( string $token, string $secret)
  {
      $this->setToken( $token )->setSecret( $secret );
  }


  /**
   * set the token
   *
   * @since 1.0
   *
   *
   * @param string $token major token
   *
   * @return self $this
   *
   */
  final protected function setToken( $token ) : self
  {
    $this->token = $token;
    return $this;
  }



  /**
   * set the secret
   *
   * @since 1.0
   *
   *
   * @param string $secret major token
   *
   * @return self $this
   *
   */
  final protected function setSecret( $secret ) : self
  {
    $this->secret = $secret;
    return $this;
  }



  /**
   * get the secret
   *
   * @since 1.0
   *
   *
   * @return string $this->secret
   *
   */
  final public function getSecret()
  {
    return $this->secret;
  }



  /**
   * get the token
   *
   * @since 1.0
   *
   *
   * @return string $this->secret
   *
   */
  final public function getToken()
  {
    return $this->token;
  }


}
