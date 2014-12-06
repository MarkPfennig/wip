<?php

/**
 * Simple data cache class, saves data to json files 
 *
 */
class DataFileCache {

  const SUBDIR = '';
  const EXTENSION = '.json';
  
  public static function store($id, $data) {
    return file_put_contents( self::idToPath($id), json_encode($data, JSON_PRETTY_PRINT) );
  }

  public static function get($id) {
    return file_get_contents( self::idToPath($id) );
  }

  public static function remove($id) {
    return unlink( self::idToPath($id) );
  }
  
  public static function all() {
    $files = array();
    foreach(new DirectoryIterator(self::dir()) as $i) {
      if($i->isDot()) continue;
      $files[] = str_replace(self::EXTENSION, '', $i->getFileName());
    }
    return $files;
  }

  public static function exists($id) {
    return file_exists( self::idToPath($id) );
  }

  private static function dir() {
    return MT_DIR_DATA . self::subdir();
  }
  
  private static function idToPath($id) {
    return self::dir() . $id . self::EXTENSION;
  }

  private static function subdir() {
    return static::SUBDIR ? static::SUBDIR . DIRECTORY_SEPARATOR : '';
  }
  
  public static function sanity() {
    if(!file_exists(self::dir())) mkdir(self::dir(), 0775);
  }
  
}

class MailDataCache extends DataFileCache {
  const SUBDIR = 'mail';
}

MailDataCache::sanity();
