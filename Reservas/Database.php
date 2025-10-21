<?php
class Database {
  private static $instance = null;
  private $pdo;

  private function __construct($config) {
    $dsn = "mysql:host={$config['db']['host']};dbname={$config['db']['dbname']};charset={$config['db']['charset']}";
    $this->pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false
    ]);
  }

  public static function getInstance($config) {
    if (!self::$instance) self::$instance = new Database($config);
    return self::$instance->pdo;
  }
}
