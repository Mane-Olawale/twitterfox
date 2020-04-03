<?php

require 'init.php';

use TwitterFox\TwitterFox;

$TwitterFox = new TwitterFox($keys[0], $keys[1], $keys[2], $keys[3]);

//$data = $TwitterFox->search('@mane_olawale');
echo '<pre>';
var_dump($TwitterFox->sendDirectMessage('kvngtobbie_', "How far guy"));
echo '</pre>';

//echo \TwitterFox\Utility\Utility::__urldecode('GET&https%3A%2F%2Fapi.twitter.com%2F1.1%2Fsearch%2Ftweets.json&oauth_consumer_key%3Dy79B8KZQZbhMe9BeEpgfd73Bj%26oauth_nonce%3D1ac725f2db165d071e4cce542f18a023%26oauth_signature_method%3DHMAC_SHA1%26oauth_timestamp%3D1585325994%26oauth_token%3D4904165841-VtOVc3P7WzBwLxJ82PyDsoZeJBAgjXm98vn6IKO%26oauth_version%3D1.0%26q%3D%2540mane_olawale');
