<?php

require 'init.php';

use TwitterFox\TwitterFox;

$TwitterFox = new TwitterFox($keys[0], $keys[1], $keys[2], $keys[3]);

//$data = $TwitterFox->search('@mane_olawale');
echo '<pre>';
$data = ($TwitterFox->users()->friendships_lookup(['4904165841', 'zaheeto', 'gnome', 'iamCynthiaPeter', 'wizkidayo', 'kvngtobbie_']));
echo '</pre>';

foreach ($data as $user) {
  echo " <br/> <strong>{$user->name}</strong> <small>@{$user->screen_name}</small>: {$user->id} <br/>
               <i> ";
               foreach ($user->connections as $value) {
                 echo "{$value},";
               }
  echo "</i>";

}
