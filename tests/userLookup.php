<?php

require 'init.php';

use TwitterFox\TwitterFox;

$TwitterFox = new TwitterFox($keys[0], $keys[1], $keys[2], $keys[3]);

//$data = $TwitterFox->search('@mane_olawale');

echo '<pre>';
$data = ($TwitterFox->users()->lookup(['4904165841', 'zaheeto', 'gnome', 'iamCynthiaPeter', 'wizkidayo', 'kvngtobbie_'], [
  "include_entities" => "true"
]));
echo '</pre>';

foreach ($data as $user) {
  echo " <br/> <strong>{$user->get_name()}</strong> <small>@{$user->get_screen_name()}</small>: {$user->get_description()} <br/>
               <i> [{$user->get_followings_readable()}] | [{$user->get_followers_readable()}] | Joined {$user->get_created_at_formatted('M d Y')}</i> <br/> <br/> ";

}
