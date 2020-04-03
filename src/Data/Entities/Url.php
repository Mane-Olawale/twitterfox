<?php

namespace TwitterFox\Data\Entities;

use TwitterFox\TwitterFox as TF;


/**
 * This class holds the url Entities object after been Fetched.
 *
 * @since 1.0
 *
 */
class Url
{



	/**
	* Signature method
	*
	* @since 1.0
	* @var \stdClass
	*/
  public $load = null;



	/**
	* Signature method
	*
	* @since 1.0
	* @var TF
	*/
  public $TwitterFox = null;



  function __construct(TF $TwitterFox, \stdClass $load)
  {

    $this->TwitterFox = $TwitterFox;

    $this->load = $load;

  }






  /**
   * Check if the object is properly initialised
   *
   * @since 1.0
   *
   *
   * @throw payloadExeption string $type to detect the media type
   *
   *
   */
  public function checkAndThrow()
  {

    if (!(!is_null($this->load) && $this->load instanceof \stdClass)){
      die ("Wrong payload data loaded to the object.");
    }

  }



  ///////////////////////////   GETTERS   /////////////////////////////////






  /**
   * Get the user payload.
   *
   * @since 1.0
   *
   *
   * @return stdClass $this->load
   *
   */
  public function load() : stdClass
  {
    $this->checkAndThrow();

    return $this->load;

  }



  /**
   * Get the TwitterFox object.
   *
   * @since 1.0
   *
   *
   * @return stdClass $this->TwitterFox
   *
   */
  public function TwitterFox() : TF
  {
    $this->checkAndThrow();

    return $this->TwitterFox;

  }




}
