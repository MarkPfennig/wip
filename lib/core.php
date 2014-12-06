<?php

#require_once MT_DIR_LIB . 'http.php';
#require_once MT_DIR_LIB . 'global.php';
#require_once MT_DIR_LIB . 'data.php';

if(defined('REQUIRE_SECURITY') && REQUIRE_SECURITY) {
  #require_once MT_DIR_LIB . 'security.php';
  #require_once MT_DIR_LIB . 'user.php';
}

if(defined('REQUIRE_REDIS') && REQUIRE_REDIS) {
  #require_once MT_DIR_LIB . 'redis.php';
  #require_once MT_DIR_LIB . 'engine.php';
}

if(defined('REQUIRE_GRAPH') && REQUIRE_GRAPH) {
  #require_once MT_DIR_LIB . 'graphdb.php';
  #require_once MT_DIR_LIB . 'graphquery.php';
}

if(defined('REQUIRE_WALLET') && REQUIRE_WALLET) {
  #require_once MT_DIR_LIB . 'crypto/loader.php';
  #require_once MT_DIR_LIB . 'wallet.php';
}
