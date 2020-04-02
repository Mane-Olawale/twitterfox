<?php

<?php


namespace TwitterFox\Data;

use TwitterFox\TwitterFox as TF;


/**
 * This class holds the user object after been Fetched.
 * @since 1.0
 */
class User
{



	/**
	* Signature method
	*
	* @since 1.0
	* @var \stdClass
	*/
  public static $load = null;



	/**
	* Signature method
	*
	* @since 1.0
	* @var TF
	*/
  public static $TwitterFox = null;



  function __construct(TF $TwitterFox, \stdClass $load)
  {

    $this->TwitterFox = $TwitterFox;

    $this->load = $load;

  }



}
