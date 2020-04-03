<?php


namespace TwitterFox\Data;

use TwitterFox\TwitterFox as TF;

use TwitterFox\Utility\Utility;


/**
 * This class holds the user object after been Fetched.
 * @since 1.0
 */
class User
{



	/**
	* This property holds the payload of the class.
	*
	* @since 1.0
	* @var \stdClass
	*/
  private $load = null;



	/**
	* This property holds the TwitterFox object.
	*
	* @since 1.0
	* @var TF
	*/
  private $TwitterFox = null;



	/**
	* This property holds location objects of a user
	*
	* @since 1.0
	* @var array
	*/
  private $locationObjects = [];



	/**
	* This holds the url entity object of user profile url
	*
	* @since 1.0
	* @var array
	*/
  private $url_entities = [];



	/**
	* This holds the url entity object of user profile description
	*
	* @since 1.0
	* @var array
	*/
  private $desc_url_entities = [];



	/**
	* This holds the hashtags entity object of user profile description
	*
	* @since 1.0
	* @var array
	*/
  private $desc_hashtags_entities = [];



	/**
	* This holds the mentions entity object of user profile description
	*
	* @since 1.0
	* @var array
	*/
  private $desc_mentions_entities = [];



	/**
	* This holds the media entity object of user profile description
	*
	* @since 1.0
	* @var array
	*/
  private $desc_media_entities = [];



	/**
	* This holds the symbols entity object of user profile description
	*
	* @since 1.0
	* @var array
	*/
  private $desc_symbols_entities = [];



  function __construct(TF $TwitterFox, \stdClass $load)
  {

    $this->TwitterFox = $TwitterFox;

    $this->load = $load;

    if (isset($this->load()->derived)){
      foreach ($this->load()->derived->locations as $value) {

        $this->$locationObjects[] = new Location($value);

      }
    }


    if (isset($this->load()->entities->url)){

      foreach ($this->load()->entities->url->urls as $value) {
        $this->url_entities[] = new Entities\Url( $this->TwitterFox(), $value);
      }

    }

    if (isset($this->load()->entities->description->urls)){
        foreach ($this->load()->entities->description->urls as $value) {
          $this->desc_url_entities[] = new Entities\Url( $this->TwitterFox(), $value);
        }
    }

    if (isset($this->load()->entities->description->hashtags)){

        foreach ($this->load()->entities->description->hashtags as $value) {

          $this->desc_hashtags_entities[] = new Entities\Hashtags( $this->TwitterFox(), $value);

        }

    }

    if (isset($this->load()->entities->description->media)){

        foreach ($this->load()->entities->description->media as $value) {
          $this->desc_media_entities[] = Entities\Media::MediaFactory($value->type, $this->TwitterFox(), $value);
        }

    }

    if (isset($this->load()->entities->description->user_mentions)){
        foreach ($this->load()->entities->description->user_mentions as $value) {
          $this->desc_mentions_entities[] = new Entities\Mentions( $this->TwitterFox(), $value);
        }
    }

    if (isset($this->load()->entities->description->symbols)){
        foreach ($this->load()->entities->description->symbols as $value) {
          $this->desc_symbols_entities[] = new Entities\Symbols( $this->TwitterFox(), $value);
        }
    }



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
 * @return TF $this->TwitterFox
 *
 */
public function TwitterFox() : TF
{
  $this->checkAndThrow();

  return $this->TwitterFox;

}


/**
 * Get the user id
 *
 * @since 1.0
 *
 *
 * @return int $this->load()->id
 *
 */
public function get_id()
{
  $this->checkAndThrow();

  return $this->load()->id;

}


/**
 * Get the user string id
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->id_str
 *
 */
public function get_id_str()
{
  $this->checkAndThrow();

  return $this->load()->id_str;

}


/**
 * Get the user name
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->name
 *
 */
public function get_name()
{
  $this->checkAndThrow();

  return $this->load()->name;

}


/**
 * Get the user screen_name
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->screen_name
 *
 */
public function get_screen_name()
{
  $this->checkAndThrow();

  return $this->load()->screen_name;

}


/**
 * Get the user location
 *
 * @since 1.0
 *
 *
 * @return array $this->load()->location
 *
 */
public function get_location()
{
  $this->checkAndThrow();

  return $this->load()->location;

}


/**
 * Get the user derived
 *
 * @since 1.0
 *
 *
 * @return array $this->load()->derived
 *
 */
public function get_derived_locations()
{
  $this->checkAndThrow();

  return  $this->locationObjects;

}


/**
 * Get the user url
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->url
 *
 */
public function get_url()
{
  $this->checkAndThrow();

  return  $this->load()->url;

}


/**
 * Get the user parsed url
 *
 * @since 1.0
 *
 *
 * @return array
 *
 */
public function get_url_parsed()
{
  $this->checkAndThrow();

  if (empty( $this->load()->url )){
    return '';
  }

  return  parse_url( $this->load()->url );

}


/**
 * Get the user description
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->description
 *
 */
public function get_description()
{
  $this->checkAndThrow();

  return  $this->load()->description;

}


/**
 * check if user has protected their tweets
 *
 * @since 1.0
 *
 *
 * @return bool $this->load()->protected
 *
 */
public function is_protected()
{
  $this->checkAndThrow();

  return  ($this->load()->protected) ? true : false;

}


/**
 * check if user has been verified
 *
 * @since 1.0
 *
 *
 * @return bool $this->load()->verified
 *
 */
public function is_verified()
{
  $this->checkAndThrow();

  return  ($this->load()->verified) ? true : false;

}


/**
 * Get The number of followers this account currently has. Under certain conditions of duress,
 * this field will temporarily indicate “0”.
 *
 * @since 1.0
 *
 *
** @return string $this->load()->followers_count
**
**/
public function get_followers_count()
{
  $this->checkAndThrow();

  return  $this->load()->followers_count;

}


/**
 * Get The approximated number of followers with unit
 *
 * @since 1.0
 *
 *
** @return string $this->load()->followers_count
**
**/
public function get_followers_readable()
{

  $this->checkAndThrow();

  $count = Utility::humanReadableCount( (int)$this->load()->followers_count );

  return $count->count.$count->unit;

}


/**
 * Get The number of users this account is following (AKA their “followings”). Under certain conditions of duress,
 * this field will temporarily indicate “0”.
 *
 * @since 1.0
 *
 *
 * @return int $this->load()->friends_count
 *
 */
public function get_followings_count()
{
  $this->checkAndThrow();

  return  $this->load()->friends_count;

}


/**
 * Get The approximated number of following with unit
 *
 * @since 1.0
 *
 *
** @return string $this->load()->friends_count
**
**/
public function get_followings_readable()
{

  $this->checkAndThrow();

  $count = Utility::humanReadableCount( (int)$this->load()->friends_count );

  return $count->count.$count->unit;

}




/**
 * Get The number of public lists that this user is a member of.
 *
 * @since 1.0
 *
 *
 * @return int $this->load()->listed_count
 *
 */
public function get_listed_count()
{
  $this->checkAndThrow();

  return  $this->load()->listed_count;

}


/**
 * Get The approximated number of lists with unit
 *
 * @since 1.0
 *
 *
** @return string $this->load()->listed_count
**
**/
public function get_listed_readable()
{

  $this->checkAndThrow();

  $count = Utility::humanReadableCount( (int)$this->load()->listed_count );

  return $count->count.$count->unit;

}




/**
 * The number of Tweets this user has liked in the account’s lifetime.
 * British spelling used in the field name for historical reasons.
 *
 * @since 1.0
 *
 *
 * @return int $this->load()->favourites_count
 *
 */
public function get_favourites_count()
{
  $this->checkAndThrow();

  return  $this->load()->favourites_count;

}


/**
 * Get The approximated number of favourites with unit
 *
 * @since 1.0
 *
 *
** @return string $this->load()->favourites_count
**
**/
public function get_favourites_readable()
{

  $this->checkAndThrow();

  $count = Utility::humanReadableCount( (int)$this->load()->favourites_count );

  return $count->count.$count->unit;

}




/**
 * The number of Tweets (including retweets) issued by the user.
 *
 * @since 1.0
 *
 *
 * @return int $this->load()->statuses_count
 *
 */
public function get_statuses_count()
{
  $this->checkAndThrow();

  return  $this->load()->statuses_count;

}


/**
 * Get The approximated number of statuses with unit
 *
 * @since 1.0
 *
 *
** @return string $this->load()->statuses_count
**
**/
public function get_statuses_readable()
{

  $this->checkAndThrow();

  $count = Utility::humanReadableCount( (int)$this->load()->statuses_count );

  return $count->count.$count->unit;

}


/**
 * check if the user has not altered the theme or background of their user profile.
 *
 * @since 1.0
 *
 *
 * @return bool $this->load()->default_profile
 *
 */
public function is_default_profile()
{
  $this->checkAndThrow();

  return  ($this->load()->default_profile) ? true : false;

}


/**
 * check if the user has not uploaded their own profile image and a default image is used instead.
 *
 * @since 1.0
 *
 *
 * @return bool $this->load()->default_profile_image
 *
 */
public function is_default_profile_image()
{
  $this->checkAndThrow();

  return  ($this->load()->default_profile_image) ? true : false;

}


/**
 * Get  a list of uppercase two-letter country codes this content is withheld from.
 * Twitter supports the following non-country values for this field:
 * “XX” - Content is withheld in all countries,
 * “XY” - Content is withheld due to a DMCA request.
 *
 * @since 1.0
 *
 *
 * @return array $this->load()->withheld_in_countries
 *
 */
public function get_withheld_in_countries()
{
  $this->checkAndThrow();

  return  $this->load()->withheld_in_countries;

}


/**
 * When present, indicates that the content being withheld is a “user.”
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->withheld_scope
 *
 */
public function get_withheld_scope()
{
  $this->checkAndThrow();

  return  $this->load()->withheld_scope;

}


/**
 * Get The HTTPS-based URL pointing to the standard web representation of the user’s uploaded profile banner.
 * By adding a final path element of the URL, it is possible to obtain different image sizes optimized for specific displays
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->profile_banner_url
 *
 */
public function get_profile_banner_url()
{
  $this->checkAndThrow();

  return  $this->load()->profile_banner_url;

}


/**
 * Get The Blob of user`s uploaded profile banner.
 *
 *
 * @since 1.0
 *
 *
 * @return resource $this->load()->profile_banner_url
 *
 */
public function get_profile_banner_url_blob()
{
  $this->checkAndThrow();

  return  utility::httpGetContents($this->load()->profile_banner_url);

}


/**
 * Get the HTTPS-based URL pointing to the user’s profile image
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->profile_image_url_https
 *
 */
public function get_profile_image_url_https()
{
  $this->checkAndThrow();

  return  $this->load()->profile_image_url_https;

}


/**
 * Get The Blob of user’s profile image
 *
 *
 * @since 1.0
 *
 *
 * @return resource $this->load()->profile_image_url_https
 *
 */
public function get_profile_image_url_https_blob()
{
  $this->checkAndThrow();

  return  utility::httpGetContents( $this->load()->profile_image_url_https );

}


/**
 * Get The UTC datetime that the user account was created on Twitter.
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->created_at
 *
 */
public function get_created_at()
{
  $this->checkAndThrow();

  return  $this->load()->created_at;

}


/**
 * Get The UTC datetime that the user account was created on Twitter.
 *
 * @since 1.0
 *
 *
 * @return string $this->load()->created_at
 *
 */
public function get_created_at_formatted( string $format ) : string
{
  $this->checkAndThrow();

  return  date( $format, strtotime($this->load()->created_at));

}




/**
 * Get the url Entities of user profile url
 *
 * @since 1.0
 *
 *
 * @return array $this->url_entities
 *
 */
public function get_url_entities() : array
{
  $this->checkAndThrow();

  return $this->url_entities;

}




/**
 * Get the url Entities of user profile description
 *
 * @since 1.0
 *
 *
 * @return array $this->desc_url_entities
 *
 */
public function get_desc_url_entities() : array
{
  $this->checkAndThrow();

  return $this->desc_url_entities;

}




/**
 * Get the hashtags Entities of user profile description
 *
 * @since 1.0
 *
 *
 * @return array $this->desc_hashtags_entities
 *
 */
public function get_desc_hashtags_entities() : array
{
  $this->checkAndThrow();

  return $this->desc_hashtags_entities;

}




/**
 * Get the mentions Entities of user profile description
 *
 * @since 1.0
 *
 *
 * @return array $this->desc_mentions_entities
 *
 */
public function get_desc_mentions_entities() : array
{
  $this->checkAndThrow();

  return $this->desc_mentions_entities;

}




/**
 * Get the media Entitie of user profile description
 *
 * @since 1.0
 *
 *
 * @return array $this->desc_media_entities
 *
 */
public function get_desc_media_entities() : array
{
  $this->checkAndThrow();

  return $this->desc_media_entities;

}




/**
 * Get the symbols Entitie of user profile description
 *
 * @since 1.0
 *
 *
 * @return array $this->desc_symbols_entities
 *
 */
public function get_desc_symbols_entities() : array
{
  $this->checkAndThrow();

  return $this->desc_symbols_entities;

}


}
