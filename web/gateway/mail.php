<?php
define('REQUIRE_REDIS', true);
require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'application.php';
  
$mail = (object)$_POST;

// check for required fields
if( !isset($mail->token) || !isset($mail->signature) || !isset($mail->timestamp) ) mail_gateway_fail();

// check for valid signature
if( !mail_gateway_validate($mail) ) mail_gateway_fail();

// cache
if(FALSE === MailDataCache::store( $mailid = mail_gateway_id($mail), $mail ) ) {
  mail_gateway_error('Cache Failed');
}

// Queue for processing
GatewayMailQueue::push($mailid);

// success
mail_gateway_accept();

function mail_gateway_accept() {
  header('HTTP/1.1 200 OK');
  echo "OK";
  exit;
}

function mail_gateway_error($reason='Internal Server Error') {
  header('HTTP/1.1 500 Internal Server Error');
  echo $reason;
  exit;
}

function mail_gateway_fail() {
  header('HTTP/1.1 406 Not Acceptable');
  echo "Not Acceptable";
  exit;
}

function mail_gateway_id($mail) {
  return hash('sha256', $mail->timestamp . $mail->token . $mail->signature );
}

function mail_gateway_validate($mail) {
  // compute hmac signature of mail and check it matches
  return $mail->signature == hash_hmac('sha256', $mail->timestamp . $mail->token, 'key-b4389e8f3a37033320d584d16d9fa92b');
}
