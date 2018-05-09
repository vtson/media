<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 16:50
 */

class Database
{
  public $db;
  public static $instance = null;

  private function __construct()
  {
    $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
  }

  public static function instance()
  {
    if (is_null(self::$instance))
      self::$instance = new Database();
    return self::$instance;
  }

  public function insert($table, $parameters)
  {

    $query = sprintf(
      'insert into %s (%s) values (%s)',

      $table,

      implode(', ', array_keys($parameters)),

      ':' . implode(', :', array_keys($parameters))
    );

    try {
      $statement = $this->db->prepare($query);

      $statement->execute($parameters);

    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function update($table, $parameters, $id)
  {

    $values = array();

    foreach ($parameters as $name => $value) {
      $values[] = "{$name} = '{$value}'";
    }

    $sql = sprintf(
      "update %s set %s where id = %s",

      $table,

      implode(', ', $values),

      $id
    );

    try {
      $statement = $this->db->prepare($sql);
      $statement->execute($values);
    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  public function returnData($query){
    $statement = $this->db->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function execute($query){
    $statement = $this->db->prepare($query);
    $statement->execute();
    return;
  }
}