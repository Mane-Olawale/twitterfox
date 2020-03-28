<?php


namespace TwitterFox\Request;

use TwitterFox\Utility\Utility;
use TwitterFox\TwitterFox;
use TwitterFox\Signature\HmacSha1;


/**
 * This class is used to send Http Requests to Twitter api
 */
class Request
{



	/**
	* Route of the endpoint
	*
	* @since 1.0
	* @var object
	*/
  protected $responseData = '';



	/**
	* Route of the endpoint
	*
	* @since 1.0
	* @var string
	*/
  protected $endpoint = null;



	/**
	* Http Request method
	*
	* @since 1.0
	* @var string
	*/
  protected $method = null;



	/**
	* Data to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  protected $data = [];



	/**
	* Files to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  protected $files = [];



	/**
	* Curl Files to send along with the request
	*
	* @since 1.0
	* @var array
	*/
  protected $curlFiles = [];



	/**
	* This is the TwitterFox object
	*
	* @since 1.0
	* @var TwitterFox
	*/
  public $TwitterFox = null;



	/**
	* This is the data method for the request
	*
	* @since 1.0
	* @var string
	*/
  protected $dataMethod = 'NORMAL';



	/**
	* This is the TwitterFox object
	*
	* @since 1.0
	* @var signature
	*/
  protected $signature = null;



	/**
	* This is the TwitterFox object
	*
	* @since 1.0
	* @var string
	*/
  protected $signatureClass = "TwitterFox\Signature\HmacSha1";



	/**
	* The curl request header
	*
	* @since 1.0
	* @var array
	*/
  protected $header = [];



	/**
	* The curl request oauth header
	*
	* @since 1.0
	* @var array
	*/
  protected $oauth = [];



	/**
	* The curl request options
	*
	* @since 1.0
	* @var array
	*/
  protected $options = [
		CURLOPT_TIMEOUT => 20,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_USERAGENT => TwitterFox::USER_AGENT,
	];


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
  function __construct(TwitterFox $twitterfox, string $endpoint, string $method, array $data = [], array $files = [])
  {
     $this->TwitterFox = $twitterfox;


    $this->addOauth([
			'oauth_version' => TwitterFox::API_VERSION,
			'oauth_nonce' => Utility::generate_nonce(),
			'oauth_timestamp' => Utility::generate_timestamp(),
			'oauth_consumer_key' => $this->TwitterFox->consumer->getToken(),
		]);


   $this->addData($data);


   $this->addFile($files);

    if ($this->TwitterFox->access){
        $this->addOauth([
      		'oauth_token' => $this->TwitterFox->access->getToken(),
      	]);
    }

    $this->endpoint = $endpoint;
    $this->method = $method;

    $this->adjustEndpoint()->adjustMethod()->addFile($files);

		$this->addOauth([
			'oauth_signature_method' => $this->signatureClass::$method
    ]);


    $this->signature = new $this->signatureClass (
      $this->getSignatureBaseString(),
      $this->getSignatureKey()
    );




  }


  public static function factory(TwitterFox $twitterfox, string $http_method, string $http_url, array $data = [], array $files = [])
  {

    return new static($twitterfox, $http_url, $http_method, $data, $files);

  }



  /**
   * Sign a request
   *
   * @since 1.0
   *
   *
   * @return self
   *
   */
  public function sign( ) : self
  {

    $this->signature->sign()->is_signed();

		$this->addOauth([
			'oauth_signature' => $this->getSignature()->getSignature()
    ]);


    return $this;

  }



  /**
   * Execute the request and return the data
   *
   * @since 1.0
   *
   *
   * @return string
   *
   */
  public function exec()
  {

    $this->filesToCurlFiles();
    $this->addData($this->curlFiles, true);

    $this->addHeader('Expect:');

    $data = $this->getData();
    $resource = $this->getEndpoint();

    if ($this->dataMethod == 'JSON'){

      $data = json_encode($data);
      $this->addHeader('Content-Type: application/json');

    }

    $this->addHeader($this->OauthHeader());

    if ($this->getMethod() === 'GET' && $data) {

			$resource .= '?' . http_build_query($data, '', '&');

		}


    if (!$this->is_signed()){

      throw new \Exception("This object is not signed. use the ->sign() method before ->exec() method.");

    }

		$options = [
			CURLOPT_URL => $resource,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $this->header,
		] + $this->options;

		if ($this->getMethod() === 'POST') {
			$options += [
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_SAFE_UPLOAD => true,
			];
		} elseif ($this->getMethod() === 'DELETE') {
			$options += [
				CURLOPT_CUSTOMREQUEST => 'DELETE',
			];
		}

		$request = curl_init();
		curl_setopt_array($request, $options);
		$result = curl_exec($request);
		if (curl_errno($request)) {
			throw new \Exception('Server error: ' . curl_error($request));
		}

		if (strpos(curl_getinfo($request, CURLINFO_CONTENT_TYPE), 'application/json') !== false) {
      //Decode json to an object tree
			$dataObj = @json_decode($result, false, 128, JSON_BIGINT_AS_STRING);
			if ($dataObj === false) {
				throw new \Exception('Invalid server response');
			}
		}

		$resCode = curl_getinfo($request, CURLINFO_HTTP_CODE);
		if ($resCode >= 400) {
			throw new \Exception(isset($dataObj->errors[0]->message)
				? $dataObj->errors[0]->message
				: "Server error #$resCode with answer $result",
				$resCode
			);
		}

    $this->setResponseData( $dataObj );
  }



  /**
   * Get signature base string
   *
   * @since 1.0
   *
   *
   * @return array $this request data
   *
   */
  public function getSignatureBaseString() : string
  {
		$parts = [
			$this->getMethod(),
			$this->getEndpoint(),
			$this->getSignableParameters(),
		];

		$parts = Utility::__urlencode($parts);

		return implode('&', $parts);
  }



  /**
   * Get signature key
   *
   * @since 1.0
   *
   *
   * @return string $key request data
   *
   */
  public function getSignatureKey() : string
  {

		$key_parts = [
			$this->TwitterFox->consumer->getSecret(),
			$this->TwitterFox->access ? $this->TwitterFox->access->getSecret() : '',
		];

		$key_parts = Utility::__urlencode( $key_parts );
		$key = implode('&', $key_parts);

    return $key;

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
   * Get request oauth
   *
   * @since 1.0
   *
   *
   * @return array $this->oauth request data
   *
   */
  public function getOauth() : array
  {
    return $this->oauth;
  }



  /**
   * Get request oauth
   *
   * @since 1.0
   *
   *
   * @return object|string $this->oauth request data
   *
   */
  public function getResponseData()
  {
    return $this->responseData;
  }



  /**
   * Get request oauth
   *
   * @since 1.0
   *
   *
   * @return self $this request data
   * @param object $payload request data
   *
   */
  public function setResponseData( $payload )
  {
    $this->responseData = $payload;

    return $this;
  }



  /**
   * Get request oauth
   *
   * @since 1.0
   *
   *
   * @return array $this->oauth request data
   *
   */
  public function is_signed() : bool
  {
    return $this->signature->is_signed();
  }



  /**
   * Get request headers
   *
   * @since 1.0
   *
   *
   * @return array $this->header request data
   *
   */
  public function getHeader() : array
  {
    return $this->header;
  }



  /**
   * Get endpoint
   *
   * @since 1.0
   *
   *
   * @return string $this->endpoint
   *
   */
  public function getEndpoint() : string
  {
    return $this->endpoint;
  }



  /**
   * Get request Method
   *
   * @since 1.0
   *
   *
   * @return string $this->method
   *
   */
  public function getMethod() : string
  {
    return $this->method;
  }



  /**
   * Get request signature
   *
   * @since 1.0
   *
   *
   * @return object $this->signature
   *
   */
  public function getSignature() : object
  {
    return $this->signature;
  }



  /**
   * Get request data from key
   *
   * @since 1.0
   *
   *
   * @return array $this->data[$key] request data
   *
   */
  public function getKeyData(string $key )
  {
    if (isset($this->data[$key])) return $this->data[$key];

    return null;
  }




  /**
   * This method appends data to the request
   *
   * @since 1.0
   *
   *
   * @param array $data data snippet array
   *
   */
  public function addData(array $data = [], $overwrite = false)
  {
    foreach ($data as $key => $value) {


      if (substr($key, 0, 5) == 'oauth') {
       throw new \Exception('You can not add oauth parameter to the request data Use ->addOauth() instead.');
      }

      if ($this->getKeyData($key) && $overwrite === false){
        if (is_array($this->getKeyData($key))){
          $this->data[$key][] = $value;
        }else{
          $this->data[$key] = [$this->data[$key], $value];
        }
      }else{
        $this->data[$key] = $value;
      }


    }
    return $this;
  }




  /**
   * This method appends oauth parameter to the request
   *
   * @since 1.0
   *
   *
   * @param array $data data snippet array
   *
   */
  public function addOauth(array $data = [])
  {
    foreach ($data as $key => $value) {



        if (substr($key, 0, 5) != 'oauth') {
         throw new \Exception('You can only add oauth parameter to the request oauth header Use ->addData() or ->addFile() instead.');
        }

      if (isset($this->oauth[$key])){
        throw new \Exception("You can not have duplicated oauth parameters.");
      }else{
        $this->oauth[$key] = $value;
      }


    }
    return $this;
  }




  /**
   * This method appends header string to the request header
   *
   * @since 1.0
   *
   *
   * @param string|array $data data snippet array
   *
   */
  public function addHeader($data)
  {
    if (is_array( $data )){
      foreach ($data as $value) {

        $this->header[] = $value;

      }
    }else{
      $this->header[] = $data;
    }
    return $this;
  }




  /**
   * This method appends File to the request
   *
   * @since 1.0
   *
   *
   * @param array $data data snippet array
   *
   */
  public function addFile(array $data = [], $overwrite = false)
  {
    foreach ($data as $key => $value) {

      if (substr($key, 0, 5) == 'oauth') {
       throw new \Exception('You can not add oauth parameter to the request files Use ->addOauth() instead.');
      }

      if (isset( $this->files[$key] ) && $overwrite === false){
        if (is_array($this->files[$key])){
          $this->files[$key][] = $value;
        }else{
          $this->files[$key] = [$this->files[$key], $value];
        }
      }else{
        $this->files[$key] = $value;
      }


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
				throw new \Exception("Cannot read the file $file. Check if file exists on disk and check its permissions.");
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
   * @return self
   *
   */
  public function adjustEndpoint() : self
  {

    		if (!strpos($this->endpoint, '://')) {
    			if (!strpos($this->endpoint, TwitterFox::RES_EXT)) {
    				$this->endpoint .= TwitterFox::RES_EXT;
    			}
    			if (substr($this->endpoint, 0, strlen(TwitterFox::BASE_PATH) ) != TwitterFox::BASE_PATH) {
    				$this->endpoint = TwitterFox::BASE_PATH.ltrim($this->endpoint, '/');
    			}
    			$this->endpoint = TwitterFox::API_URL . $this->endpoint;
    		}


    		$parts = parse_url($this->endpoint);

    		$scheme = (isset($parts['scheme'])) ? $parts['scheme'] : 'http';
    		$port = (isset($parts['port'])) ? $parts['port'] : (($scheme == 'https') ? '443' : '80');
    		$host = (isset($parts['host'])) ? $parts['host'] : '';
    		$path = (isset($parts['path'])) ? $parts['path'] : '';

    		if (($scheme == 'https' && $port != '443')
    				|| ($scheme == 'http' && $port != '80')) {
    			$host = "$host:$port";
    		}

		    $this->endpoint = "$scheme://$host$path";

        return $this;
  }


  /**
   * Adjust the resoure
   *
   * @since 1.0
   *
   *
   * @return self
   *
   */
  public function adjustMethod() : self
  {

    if ($this->method == 'POSTJSON'){
      $this->method = 'POST';
      $this->dataMethod = "JSON";
    }

    $this->method = strtoupper( $this->method );

    return $this;
  }


  /**
   * Get parameters that can be signed
   *
   * @since 1.0
   *
   *
   * @return string
   *
   */
  public function getSignableParameters() : string
  {
		// Grab all parameters
		$params = $this->getOauth();

		// Remove oauth_signature if present
		if (isset($params['oauth_signature'])) {
			unset($params['oauth_signature']);
		}

    if ($this->getMethod() == 'GET'){
      $params += $this->getData();
    }

		return Utility::build_http_query($params);
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
   public function OauthHeader( string $realm = null)
   {
     $authHeader = "Authorization: OAuth ";
     $array = [];
     if ($realm){
       $authHeader .= "realm=\"{$realm}\",";
     }

     foreach ( $this->getOauth() as $key => $value) {

      		if (substr($key, 0, 5) != 'oauth') {
      			continue;
      		}
      		if (is_array($value)) {
      			throw new \Exception('Arrays not supported in headers');
      		}
      		$array[] = Utility::__urlencode($key) . '="' . Utility::__urlencode($value) . '"';

     }

     $authHeader .= implode(',', $array);

     return $authHeader;
   }





}
