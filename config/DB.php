<?php
class DB
{
  private $connection;

  public function __construct()
  {
    $config = parse_ini_file('config.ini', true);

    $type = $config['db']['type'];
    $db_host = $config['db']['host'];
    $db_name = $config['db']['name'];
    $username = $config['db']['user'];
    $password = $config['db']['password'];

    $this->connection = new PDO(
      "$type:host=$db_host;dbname=$db_name",
      $username,
      $password,
      [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]
    );
  }

  public function getConnection()
  {
    return $this->connection;
  }
}
