<?php


namespace TwitterFox\Data;

use TwitterFox\TwitterFox as TF;


/**
 * This class holds the user object after been Fetched.
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
	* @var \stdClass
	*/
  public static $TwitterFox = null;



  function __construct(TF $TwitterFox, \stdClass $load)
  {

    $this->TwitterFox = $TwitterFox;

  }




///////////////////////////   GETTERS   /////////////////////////////////




///////////////////////////   SETTERS   /////////////////////////////////



/**
 * Get the user id
 *
 * @since 1.0
 *
 *
 * @param int $this->load->id
 *
 */
public function get_id()
{
  $this->checkAndThrow();

  return $this->load->id;

}


/**
 * Get the user string id
 *
 * @since 1.0
 *
 *
 * @param int $this->load->id_str
 *
 */
public function get_id_str()
{
  $this->checkAndThrow();

  return $this->load->id_str;

}


/**
 * Get the user name
 *
 * @since 1.0
 *
 *
 * @param int $this->load->name
 *
 */
public function get_name()
{
  $this->checkAndThrow();

  return $this->load->name;

}


/**
 * Get the user screen_name
 *
 * @since 1.0
 *
 *
 * @param int $this->load->screen_name
 *
 */
public function get_screen_name()
{
  $this->checkAndThrow();

  return $this->load->screen_name;

}


}
