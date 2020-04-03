<?php


namespace TwitterFox\Actions;


use TwitterFox\TwitterFox as TF;

use TwitterFox\Data\User;


/**
 * This class handles all the actions on the users endpoint
 *
 * @since 1.0
 *
 */
class Users
{



  /**
  * This property holds the TwitterFox object.
  *
  * @since 1.0
  * @var TF
  */
  private $TwitterFox = null;



  /**
  * This property holds the TwitterFox object.
  *
  * @since 1.0
  * @var TF
  */
  private $BasePath = [
    "users" => "users/",
    "followers" => "followers/",
    "friends" => "friends/",
    "friendships" => "friendships/"
  ];


  function __construct(TF $TwitterFox)
  {

    $this->TwitterFox = $TwitterFox;

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

    return $this->TwitterFox;

  }



  /**
   * This methods search for users.
   *
   * @since 1.0
   *
   *
   * @param string $query  The search query to run against people search.
   * @param array $options  Additional options array.
   *
   */
  public function search( string $query, array $options = [])
  {
    $allowed = [
      "page",
      "count",
      "include_entities"
    ];

    $data = ["q" => $query];
    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }
    $DATA = $this->TwitterFox->get($this->BasePath["users"].'search', $data);

    $GENDATA = [];

    foreach ($DATA as $load) {
      $GENDATA[] = new User($this->TwitterFox, $load);
    }

    unset($DATA);

    return $GENDATA;

  }






  /**
   * Get the TwitterFox object.
   *
   * @since 1.0
   *
   *
   * @param string|array $ids  The search query to run against people search.
   * @param array $options  Additional options array.
   *
   */
  public function lookup( $ids, array $options = [])
  {

    $data = [];
    $screen_name = [];
    $user_id = [];

    if ( !(is_string($ids) || is_array($ids)) ) die("Wrong id error.");

    if (is_string( $ids )){
      $ids = explode(',', $ids);
    }



    foreach ($ids as $value) {
      if ( is_numeric( $value ) ){
        $user_id[] = $value;
      }else{
        $screen_name[] = $value;
      }
    }

    $data["screen_name"] = implode(',', $screen_name);

    $data["user_id"] = implode(',', $user_id);

    $allowed = [
      "tweet_mode",
      "include_entities"
    ];

    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }
    $DATA = $this->TwitterFox->get($this->BasePath["users"].'lookup', $data);

    $GENDATA = [];

    foreach ($DATA as $load) {
      $GENDATA[] = new User($this->TwitterFox, $load);
    }

    unset($DATA);

    return $GENDATA;

  }





  /**
   * This method loads just a user
   *
   * @since 1.0
   *
   *
   * @param string $id  The search query to run against people search.
   * @param array $options  Additional options array.
   *
   */
  public function show( string $id = '', string $include_entities = "false")
  {
    $allowed = [
      "page",
      "count",
      "include_entities"
    ];


    if (is_numeric($id)){
      $data = ["user_id" => $id];
    }else if (!empty($id)){
      $data = ["screen_name" => $id];
    }

    $data['include_entities'] = $include_entities;

    $DATA = $this->TwitterFox->get($this->BasePath["users"].'show', $data);

    $GENDATA = new User($this->TwitterFox, $DATA);

    unset($DATA);

    return $GENDATA;

  }





  /**
   * This method gets list of ids of user folloing the account or a specified user
   *
   * @since 1.0
   *
   *
   * @param string $id The user id or sreen name
   * @param array $options  Additional options array.
   *
   */
  public function followers_ids( string $id = '', array $options = [])
  {
    $allowed = [
      "cursor",
      "count",
      "stringify_ids"
    ];


    if (is_numeric($id)){
      $data = ["user_id" => $id];
    }else if (!empty($id)){
      $data = ["screen_name" => $id];
    }

    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }

    $DATA = $this->TwitterFox->get($this->BasePath["followers"].'ids', $data);

    return $DATA;

  }





  /**
   * This method gets list of user data of user folloing the account or a specified user
   *
   * @since 1.0
   *
   *
   * @param string $id The user id or sreen name
   * @param array $options  Additional options array.
   *
   */
  public function followers_list( string $id = '', array $options = [])
  {
    $allowed = [
      "cursor",
      "count",
      "skip_status",
      "include_user_entities"
    ];


    if (is_numeric($id)){
      $data = ["user_id" => $id];
    }else if (!empty($id)){
      $data = ["screen_name" => $id];
    }

    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }

    $DATA = $this->TwitterFox->get($this->BasePath["followers"].'list', $data);

    $USERS = $DATA->users;

    $DATA->users = [];

    foreach ( $USERS as $load ) {

      $DATA->users[] = new User($this->TwitterFox, $load);

    }

    return $DATA;

  }





  /**
   * This method gets list of ids of user the account or a specified user follows
   *
   * @since 1.0
   *
   *
   * @param string $id The user id or sreen name
   * @param array $options  Additional options array.
   *
   */
  public function friends_ids( string $id = '', array $options = [])
  {
    $allowed = [
      "cursor",
      "count",
      "stringify_ids"
    ];


    if (is_numeric($id)){
      $data = ["user_id" => $id];
    }else if (!empty($id)){
      $data = ["screen_name" => $id];
    }

    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }

    $DATA = $this->TwitterFox->get($this->BasePath["friends"].'ids', $data);

    return $DATA;

  }





  /**
   * This method gets list of user data of userthe account or a specified user is following
   *
   * @since 1.0
   *
   *
   * @param string $id The user id or sreen name
   * @param array $options  Additional options array.
   *
   */
  public function friends_list( string $id = '', array $options = [])
  {
    $allowed = [
      "cursor",
      "count",
      "skip_status",
      "include_user_entities"
    ];


    if (is_numeric($id)){
      $data = ["user_id" => $id];
    }else if (!empty($id)){
      $data = ["screen_name" => $id];
    }

    foreach ($options as $key => $value) {

      if (in_array($key, $allowed)){
        $data[$key] = $value;
      }

    }

    $DATA = $this->TwitterFox->get($this->BasePath["friends"].'list', $data);

    $USERS = $DATA->users;

    $DATA->users = [];

    foreach ( $USERS as $load ) {

      $DATA->users[] = new User($this->TwitterFox, $load);

    }

    return $DATA;

  }





  /**
   * This method gets list of ids of user who requested to follow the account.
   *
   * @since 1.0
   *
   *
   * @param string|array $userdata The user id or sreen name
   *
   */
  public function friendships_incoming( $userdata = '' )
  {

    $allowedVal = [
      'true'
    ];

    $allowedKey = [
        'cursor',
        'stringify_ids'
    ];

    $data = [];

    if ($userdata){
      if (is_string($userdata) && is_numeric($userdata)){

        $data['cursor'] = $userdata;

      }else if (is_string($userdata) && in_array($userdata, $allowedVal)){

        $data['stringify_ids'] = $userdata;

      }else if(is_array($userdata)){

          foreach ($userdata as $key => $value) {

            if (in_array($key, $allowedKey)){
              $data[$key] = $value;
            }

          }
      }else{
        die("Data type is invalid and not useful for this action, Sting and array data types only.");
      }
    }


    $DATA = $this->TwitterFox->get($this->BasePath["friendships"].'incoming', $data);

    return $DATA;

  }





  /**
   * This method gets list of connection data of user who requested to follow the account.
   *
   * @since 1.0
   *
   *
   * @param string|array $ids The user id or sreen names list
   *
   */
  public function friendships_lookup( $ids)
  {

    $data = [];
    $screen_name = [];
    $user_id = [];

    if ( !(is_string($ids) || is_array($ids)) ) die("Wrong id error.");

    if (is_string( $ids )){
      $ids = explode(',', $ids);
    }



    foreach ($ids as $value) {
      if ( is_numeric( $value ) ){
        $user_id[] = $value;
      }else{
        $screen_name[] = $value;
      }
    }

    $data["screen_name"] = implode(',', $screen_name);

    $data["user_id"] = implode(',', $user_id);

    $DATA = $this->TwitterFox->get($this->BasePath["friendships"].'lookup', $data);

    return $DATA;

  }





  /**
   * This method Returns a collection of user_ids that the currently authenticated user does not want to receive retweets from.
   *
   * @since 1.0
   *
   *
   * @param string $ids The user id or sreen names list
   *
   */
  public function friendships_no_retweets_ids( string $stringify_ids = 'false' )
  {

    $data['stringify_ids'] = $stringify_ids;

    $DATA = $this->TwitterFox->get($this->BasePath["friendships"].'no_retweets/ids', $data);

    return $DATA;

  }






  /**
   * This method Returns a collection of numeric IDs for every protected user for whom the authenticating user has a pending follow request.
   *
   * @since 1.0
   *
   *
   * @param string|array $ids The user id or sreen names list
   *
   */
  public function friendships_outgoing( $userdata )
  {

    $allowedVal = [
      'true'
    ];

    $allowedKey = [
        'cursor',
        'stringify_ids'
    ];

    $data = [];

    if ($userdata){
      if (is_string($userdata) && is_numeric($userdata)){

        $data['cursor'] = $userdata;

      }else if (is_string($userdata) && in_array($userdata, $allowedVal)){

        $data['stringify_ids'] = $userdata;

      }else if(is_array($userdata)){

          foreach ($userdata as $key => $value) {

            if (in_array($key, $allowedKey)){
              $data[$key] = $value;
            }

          }
      }else{
        die("Data type is invalid and not useful for this action, Sting and array data types only.");
      }
    }


    $DATA = $this->TwitterFox->get($this->BasePath["friendships"].'outgoing', $data);

    return $DATA;

  }





  /**
   * This method Returns detailed information about the relationship between two arbitrary users.
   *
   * @since 1.0
   *
   *
   * @param string $source The source user id or sreen name
   * @param string $target The target user id or sreen name
   *
   */
  public function friendships_show( string $source, string $target )
  {


    if (is_numeric($source)){
      $data["source_id"] = $source;
    }else if (!empty($source)){
      $data["source_screen_name"] = $source;
    }


    if (is_numeric($target)){
      $data["target_id"] = $target;
    }else if (!empty($target)){
      $data["target_screen_name"] = $target;
    }

    $DATA = $this->TwitterFox->get($this->BasePath["friendships"].'show', $data);

    return $DATA;

  }




}
