<?php


namespace TwitterFox\Data\Entities;

use TwitterFox\TwitterFox as TF;


/**
 * This class is the abstract media Entities class for all media parents.
 *
 * @since 1.0
 *
 */
abstract class Media
{



	/**
	* Signature method
	*
	* @since 1.0
	* @var \stdClass
	*/
  private $load = null;



	/**
	* Signature method
	*
	* @since 1.0
	* @var TF
	*/
  private $TwitterFox = null;



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






  /**
   * Get the user payload.
   *
   * @since 1.0
   *
   *
   * @param string $type to detect the media type
   * @param TF $TwitterFox the twitterfox window
   * @param \stdClass $load the twitterfox window
   *
   *
   * @return VideoMedia|PhotoMedia|GifMedia  $load the twitterfox window
   *
   */
  public static function MediaFactory(string $type, TF $TwitterFox, \stdClass $load)
  {

    return $load;

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
  public function load() : \stdClass
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
