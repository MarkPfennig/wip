<?php
use Predis\Configuration\ExceptionsOption;
require_once MT_DIR_VENDOR . 'autoload.php';

Predis\Autoloader::register();

$redis = Redis::get();

/**
 * Static Redis client
 * 
 * @example Redis::get()
 */
class Redis {
  
  private static $__instance; private $client;

  private function __construct() { $this->client = new Predis\Client(); }

  /**
   * @return Predis\Client
   */
  public static function get() {
    if( self::$__instance == NULL ) self::$__instance = new self;
    return self::$__instance->client;
  }

}

/**
 * Simple Redis Locking
 */
abstract class LockingManager extends Named {

  const LOCK_TIME = 10;
  
  public static function lock() {
    if('x' !== Redis::get()->blpop(self::name() . '_lock', self::LOCK_TIME))
      throw new LockFailureException(self::name());
  }

  public static function unlock() {
    Redis::get()->lpush(self::name() . '_lock', 'x');
  }

  protected function lockandrun($function) {
    self::lock(); $r = $function(self::name()); self::unlock();
    return $r;
  }

}

class LockFailureException extends Exception {
  public function __construct($lockname) {
    parent::__construct('Cannot obtain lock '.$lockname, '20');
  }
}

/**
 * Queue Manager, simply extend to have named queues.
 *
 */
abstract class QueueManager extends Named {

  const BLOCK_TIME = 10;
  
  public static function push($id) {
    return Redis::get()->lpush( self::name(), $id );
  }

  public static function pop() {
    return Redis::get()->rpop( self::name() );
  }
  
  public static function bpop() {
    if( $x = Redis::get()->brpop( self::name(), self::BLOCK_TIME ) ) return $x[1];
    return FALSE;
  }

  public static function bpopf() {
    if( $x = Redis::get()->brpop( self::name(), self::BLOCK_TIME ) ) return $x[1];
    throw new LockFailureException(self::name());
  }
  
  public static function all() {
    return Redis::get()->lrange( self::name(), 0, -1 );
  }
  
  public static function length() {
    return Redis::get()->llen( self::name());
  }

  public static function remove($id) {
    return Redis::get()->lrem( self::name(), 0, $id );
  }

  public static function requeue($id) {
    return Redis::get()->rpush( self::name(), $id );
  }

}

class GatewayMailQueue extends QueueManager {}
class GatewayWebSocketQueue extends QueueManager {}
class NetworkTransactionQueue extends QueueManager {}

