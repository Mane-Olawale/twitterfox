<?php


namespace TwitterFox\Request;


/**
 * This class is used to send Http Requests to Twitter api
 */
class Request
{



	/**
	* Route of the endpoint
	*
	* @since 1.0
	* @var string
	*/
  public $endpoint = null;



	/**
	* Http Request method
	*
	* @since 1.0
	* @var string
	*/
  public $method = null;



	/**
	* Data to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  public $data = [];



	/**
	* Files to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  public $files = [];



	/**
	* Curl Files to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  public $curlFiles = [];


  /**
   * The initial method
   *
   * @since 1.0
   *
   *
   * @param string $endpoint path to end point
   * @param string $method http method to use for request
   * @param array $data data to send with request
   * @param string $endpoint data to send with request
   *
   */
  function __construct(string $endpoint, string $method, array $data = [], array $files = [])
  {
    // code...
  }


  public static function factory(ConsumerToken $consumer, ?AccessToken $token, string $http_method, string $http_url, array $data = [], array $file = [])
  {

  }



  /**
   * Get request data
   *
   * @since 1.0
   *
   *
   * @return array $this->data request data
   *
   */
  public function getData() : array
  {
    return $this->data;
  }




  /**
   * This method appends data to the request
   *
   * @since 1.0
   *
   *
   * @param string $data data snippet array
   *
   */
  public function addData(array $data = [])
  {
    foreach ($data as $key => $value) {

      $this->data[$key] = $value;

    }
    return $this;
  }




  /**
   * This method appends data to the request
   *
   * @since 1.0
   *
   *
   * @param string $data data snippet array
   *
   */
  public function addFile(array $data )
  {
    foreach ($data as $key => $value) {

      $this->data[$key] = $value;

    }
    return $this;
  }




  /**
   * Converts individual files to curlfiles
   *
   * @since 1.0
   *
   *
   * @return void
   *
   */
  public function filesToCurlFiles()
  {
    foreach ($this->files as $key => $file) {

			if (!is_file($file)) {
				die("Cannot read the file $file. Check if file exists on disk and check its permissions.");
			}

      $this->curlFiles[$key] = new \CURLFile($file);

    }

    return $this;
  }




  /**
   * Adjust the resoure
   *
   * @since 1.0
   *
   *
   * @return void
   *
   */
  public function adjustEndpoint()
  {

    		if (!strpos($this->endpoint, '://')) {
    			if (!strpos($this->endpoint, Utility::RES_EXT)) {
    				$this->endpoint .= Utility::RES_EXT;
    			}
    			$this->endpoint = Utility::API_URL . $this->endpoint;
    		}
  }




  /**
   * Proccess Authorization header string
   *
   * @since 1.0
   *
   *
   * @param string $realm
   *
   *
   * @return string $authHeader
   *
   */
   public function authHeaderString( string $realm = null)
   {
     $authHeader = "Authorization: OAuth ";
     $array = [];
     if ($realm){
       $authHeader .= "realm=\"{$realm}\",";
     }

     foreach ( $this->getData() as $key => $value) {

      		if (substr($key, 0, 5) != 'oauth') {
      			continue;
      		}
      		if (is_array($value)) {
      			die ('Arrays not supported in headers');
      		}
      		$array[] = Utility::rawUrlEncode($key) . '="' . Utility::rawUrlEncode($value) . '"';

     }

     $authHeader .= implode('&', $array);

     return $authHeader;
   }





}
