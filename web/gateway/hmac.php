<?php

$json = json_decode(file_get_contents('cache/1412952473.json'));

// RESOLVER

$global = (object)array(
  'twitter' => 'https://twitter.com/',
  'facebook' => 'https://facebook.com/',
  'mt' => 'http://markthis.org/',
  'vimeo' => 'http://vimeo.com/',
);

$implementation = (object)array();

$user = (object)array(
  'markus' => 'https://twitter.com/RobotMarkus'
);

$test = 'mark@twitter';

