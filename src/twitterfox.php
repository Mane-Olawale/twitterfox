<?php
namespace TwitterFox;


use TwitterFox\Signature\HmacSha1;
use TwitterFox\Token\ConsumerToken;
use TwitterFox\Token\AccessToken;
use TwitterFox\Request\Request;

/**
 * This is the Main TwitterFox Object, this Class is The
 * window to all TwitterFox functionalities
 */
class TwitterFox
{

  public const API_URL = 'https://api.twitter.com';

  public const BASE_PATH = '/1.1/';

  public const RES_EXT = '.json';

  public const API_VERSION = '1.0';

  public const USER_AGENT = 'TwitterFox PHP library';

  /**
  *This the property that stores the consumer token object
  * @since 1.0
  *
  * @var ConsumerToken
  */
  public $consumer = null;

  /**
  *This the property that stores the consumer token object
  * @since 1.0
  *
  * @var AccessToken
  */
  public $access = null;


  public function __construct(string $consumerToken, string $consumerTokenSecret, string $accessToken = null, string $accessTokenSecret = null)
  {


    $this->setConsumer(new ConsumerToken($consumerToken, $consumerTokenSecret));

    if ( $accessToken && $accessTokenSecret ){
      $this->setAccess( new AccessToken($accessToken, $accessTokenSecret) );
    }


  }





  /**
   * Request method of TwitterFox
   *
   * @since 1.0
   *
   *
   * @param array $query
   * @return object $statuses
   *
   */
  public function request(string $method, string $endpoint, array $data = [], array $files = [])
  {

    $request = Request::factory($this, $method, $endpoint, $data, $files);
    try{
      $request->sign()->exec();
    }catch (\Exception $e){
      echo $e->getMessage() . '<br>';
    }


    return $request->getResponseData();

  }





  /**
   * Request post method of TwitterFox
   *
   * @since 1.0
   *
   *
   * @param array $endpoint
   * @param array $data
   * @param array $files
   * @return stdClass $request->getResponseData()
   *
   */
  public function post( string $endpoint, array $data = [], array $files = [])
  {

    return $this->request( 'POST', $endpoint, $data, $files);


  }





  /**
   * Request get method of TwitterFox
   *
   * @since 1.0
   *
   *
   * @param array $endpoint
   * @param array $data
   * @param array $files
   * @return stdClass $request->getResponseData()
   *
   */
  public function get( string $endpoint, array $data = [], array $files = [])
  {

    return $this->request( 'GET', $endpoint, $data, $files);


  }

    ///////////////////////  PREDEFINED REQUESTS   /////////////////////////




/**
 * Search tweeets
 *
 * @since 1.0
 *
 *
 * @param array $query
 * @return object $statuses
 *
 */
public function search($query, bool $full = true)
{
	$data = $this->request('GET', 'search/tweets', ( is_array($query) ) ? $query : ['q' => $query]);

  if ( $full ){
    return $data;
  }
	return $data->statuses;
}


/**
 * Follows a user on Twitter.
 * https://dev.twitter.com/rest/reference/post/friendships/create
 * @throws Exception
 */
public function follow(string $username) : \stdClass
{
	return $this->post('friendships/create', [
    'screen_name' => $username,
    'follow' => 'true'
  ]);
}


/**
 * Unfollows a user on Twitter.
 * https://dev.twitter.com/rest/reference/post/friendships/create
 * @throws Exception
 */
public function unfollow(string $username) : \stdClass
{
	return $this->post('friendships/destroy', [
    'screen_name' => $username,
  ]);
}


/**
 * Sends a direct message to the specified user.
 *
 *
 * https://dev.twitter.com/rest/reference/post/direct_messages/new
 *
 * @throws Exception
 */
public function sendDirectMessage(string $username, string $message): \stdClass
{
	return $this->post_json(
		'direct_messages/events/new',
		['event' => [
			'type' => 'message_create',
			'message_create' => [
				'target' => ['recipient_id' => $this->loadUserInfo($username)->id_str],
				'message_data' => ['text' => $message],
			],
		]]
	);
}


  ///////////////////////////   GETTERS   /////////////////////////////////




  ///////////////////////////   SETTERS   /////////////////////////////////




  /**
   * Set the consumer token object
   *
   * @since 1.0
   *
   *
   * @return self $this
   * @param ConsumerToken $token
   *
   */
  public function setConsumer( $token )
  {

    $this->consumer = $token;

    return $this;

  }




  /**
   * Set the access token object
   *
   * @since 1.0
   *
   *
   * @return self $this
   * @param AccessToken $token
   *
   */
  public function setAccess( $token )
  {

    $this->access = $token;

    return $this;

  }



}
