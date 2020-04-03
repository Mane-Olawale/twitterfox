<?php

namespace TwitterFox\Utility;

/**
 * This class store the library details and helper functions
 */
class Utility
{


	/**
	 * This function takes a input like a=b&a=c&d=e and returns the parsed parameters like this
	 * ['a' => array('b','c'), 'd' => 'e']
   * @param string $input
   * @return array $parsed_parameter
	 */
	public static function parse_parameters(string $input): array
	{
		if (!isset($input) || !$input) {
			return [];
		}

		$pairs = explode('&', trim($input, '&'));

		$parsed_parameters = [];
		foreach ($pairs as $pair) {
			$split = explode('=', $pair, 2);
			$parameter = static::__urldecode($split[0]);
			$value = isset($split[1]) ? static::__urldecode($split[1]) : '';

			if (isset($parsed_parameters[$parameter])) {
				// We have already recieved parameter(s) with this name, so add to the list
				// of parameters with this name

				if (is_scalar($parsed_parameters[$parameter])) {
					// This is the first duplicate, so transform scalar (string) into an array
					// so we can add the duplicates
					$parsed_parameters[$parameter] = [$parsed_parameters[$parameter]];
				}

				$parsed_parameters[$parameter][] = $value;
			} else {
				$parsed_parameters[$parameter] = $value;
			}
		}
		return $parsed_parameters;
	}


	public static function __urlencode($input)
	{
		if (is_array($input)) {
			return array_map([__CLASS__, '__urlencode'], $input);
		} elseif (is_scalar($input)) {
			return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode((string) $input)));
		} else {
			return '';
		}
	}


	/**
	 * This decode function isn't taking into consideration the above
	 * modifications to the encoding process. However, this method doesn't
	 * seem to be used anywhere so leaving it as is.
	 */
	public static function __urldecode(string $string): string
	{
		return urldecode($string);
	}




	/**
	 * Builds up query string from array
   *
   * @param array $params
	 */
	public static function build_http_query(array $params): string
	{
		if (!$params) {
			return '';
		}

		// Urlencode both keys and values
		$keys = static::__urlencode(array_keys($params));
		$values = static::__urlencode(array_values($params));
		$params = array_combine($keys, $values);

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 (1)
		uksort($params, 'strcmp');

		$pairs = [];
		foreach ($params as $parameter => $value) {
			if (is_array($value)) {
				// If two or more parameters share the same name, they are sorted by their value
				// Ref: Spec: 9.1.1 (1)
				// June 12th, 2010 - changed to sort because of issue 164 by hidetaka
				sort($value, SORT_STRING);
				foreach ($value as $duplicate_value) {
					$pairs[] = $parameter . '=' . $duplicate_value;
				}
			} else {
				$pairs[] = $parameter . '=' . $value;
			}
		}
		// For each parameter, the name is separated from the corresponding value by an '=' character (ASCII code 61)
		// Each name-value pair is separated by an '&' character (ASCII code 38)
		return implode('&', $pairs);
	}



  /**
  * This generate random string of random length
  * @since 1.0
  *
  * @param int $minlength Minimum length of the string
  * @param int $maxlength Maximum length of the string
  * @param bool $useupper if the string include uppercase
  * @param bool $usenumbers if the string include number
  *
  */
	public static function hashGen( int $minlength, int $maxlength, bool $useupper, bool $usenumbers) : string
	{
      $key = '';

      $charset = "abcdefghijklmnopqrstuvwxyz";

      if ( $useupper == 1 ) $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

      if ( $usenumbers == 1 ) $charset .= "0123456789";

      if ( $minlength > $maxlength ) {
         $length = mt_rand ( $maxlength, $minlength );
      }else {
         $length = mt_rand ( $minlength, $maxlength );
      }

    for ( $i = 0; $i < $length; $i++){
      $key .= $charset[ ( mt_rand( 0, ( strlen( $charset ) - 1 ) ) ) ];
    }
    return $key;
	}



  /**
  * This generate random string of random length
  *
  * @since 1.0
  *
  * @param int $minlength Minimum length of the string
  * @param int $maxlength Maximum length of the string
  *
  */
	public static function hashNumber( int $minlength, int $maxlength ) : string
	{
      $key = '';

      $charset = "0123456789";

      if ( $minlength > $maxlength ) {
         $length = mt_rand ( $maxlength, $minlength );
      }else {
         $length = mt_rand ( $minlength, $maxlength );
      }

    for ( $i = 0; $i < $length; $i++){
      $key .= $charset[ ( mt_rand( 0, ( strlen( $charset ) - 1 ) ) ) ];
    }
    return $key;
	}



  /**
  * This generate random string of random length
  *
  * @since 1.0
  *
  * @param int $minlength Minimum length of the string
  * @param int $maxlength Maximum length of the string
  *
  */
	public static function generate_nonce( string $type = "hash") : string
	{
    if ($type == "hash"){
      return md5( static::hashGen(30, 50, true, true));
    }else{
      return md5( static::hashNumber(30, 50));
    }
	}


	/**
	 * get the current timestamp
   *
   * @return int time()
   *
	 */
	public static function generate_timestamp(): int
	{
		return time();
	}



  /**
  * This generate a human readable count
  *
  * @since 1.0
  *
  * @param int $count
  * @param int|bool $round Maximum length of the string
  *
  */
	public static function humanReadableCount( int $count, $round = 1, array $unitTemp = [
		'K' => 'K',
		'M' => 'M',
		'B' => 'B',
		'T' => 'T'
		] )
	{
		  $unit = '';

		  if ($count / 1000 > 0.99){
		     $count /= 1000;
		     $unit = $unitTemp['K'];
		  }

		  if ($count / 1000 > 0.99){
		     $count /= 1000;
		     $unit = $unitTemp['M'];
		  }

		  if ($count / 1000 > 0.99){
		     $count /= 1000;
		     $unit = $unitTemp['B'];
		  }

		  if ($count / 1000 > 0.99){
		     $count /= 1000;
		     $unit = $unitTemp['T'];
		  }

			if ($round !== false && is_int($round)){
				$count = round($count, $round);
			}

		  $result = [
				"count" => $count,
				"unit" => $unit
			];

			$result = json_encode($result);

			$result = @json_decode($result, false);


			return $result;
	}



  /**
  * This generate a human readable count
  *
  * @since 1.0
  *
  * @param string $url
  *
  */
	public static function httpGetContents( string $url, array $options = []) : string
	{

			$ch = curl_init();

	    if(!isset($options[CURLOPT_TIMEOUT])) {
	      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    }

	    if(!isset($options[CURLOPT_RETURNTRANSFER])) {
	      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 	TRUE);
	    }

			curl_setopt($ch, CURLOPT_URL, $url);

			if (is_array($options) && $options ){

				foreach ($options as $key => $value) {
					curl_setopt($ch, $key, 	$value);
				}

			}

	    if(!isset($options[CURLOPT_USERAGENT])) {

	      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['SERVER_NAME']);

	    }

			if(FALSE === ($retval = curl_exec($ch))) {
			  die(curl_error($ch));
			} else {
			  return $retval;
			}
	}




}
