<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 16:36
 */

abstract class BaseModel
{
  public $fields;
  public $table;

  private $db;

  public $sql;
  public $where;
  public $order;
  public $limit;
  public $offset;

  public function __construct()
  {
    $this->db = Database::instance();
  }

  public function setData($data = [])
  {
    $availableFields = $this->fields;
    $availableFields[] = 'id';
    foreach ($data as $key => $value) {
      if (in_array($key, $availableFields)) {
        $this->$key = $value;
      }
    }
  }

  public function save()
  {

    $data = [];
    foreach ($this as $fieldName => $fieldValue) {
      if (in_array($fieldName, $this->fields)) {
        $data[$fieldName] = $fieldValue;
      }
    }
    if (property_exists($this, 'id')) {
      $this->db->update($this->table, $data, $this->id);
    } else {
      $this->db->insert($this->table, $data);
    }
    return true;
  }

  public function select($fields = '*', $limit = null, $offset = null)
  {
    $fields = (is_array($fields)) ? implode(',', $fields) : $fields;

    $this->limit($limit, $offset);

    $this->sql(array(
      'SELECT',
      $fields,
      'FROM',
      $this->table,
      $this->where,
      $this->order,
      $this->limit,
      $this->offset
    ));

    return $this;
  }

  public function where($field, $value = null)
  {
    $join = (empty($this->where)) ? 'WHERE' : '';
    $this->where .= $this->parseCondition($field, $value, $join);
    return $this;
  }

  public function limit($limit, $offset = null)
  {
    if ($limit !== null) {
      $this->limit = 'LIMIT ' . $limit;
    }
    if ($offset !== null) {
      $this->offset($offset);
    }
    return $this;
  }

  public function offset($offset, $limit = null)
  {
    if ($offset !== null) {
      $this->offset = 'OFFSET ' . $offset;
    }
    if ($limit !== null) {
      $this->limit($limit);
    }
    return $this;
  }

  public function sql($sql = null)
  {
    if ($sql !== null) {
      $this->sql = trim(
        (is_array($sql)) ?
          array_reduce($sql, array($this, 'build')) :
          $sql
      );
      return $this;
    }
  }

  public function getMany(){
    $query = $this->sql;
    $db = Database::instance();
    $recordData = $db->returnData($query);
    $records = [];
    foreach ($recordData as $data) {
      $tmp = new static();
      $tmp->setData($data);
      $records[] = $tmp;
    }
    return $records;
  }

  public function getOne(){
    $query = $this->sql;
    $db = Database::instance();
    $recordData = $db->returnData($query);
    foreach ($recordData as $data) {
      $tmp = new static();
      $tmp->setData($data);
      $recordData = $tmp;
    }
    return $recordData;
  }

  public function delete($where = null) {
    if ($where !== null) {
      $this->where($where);
    }
    $this->sql(array(
      'DELETE FROM',
      $this->table,
      $this->where
    ));
    $db = Database::instance();
    $db->execute($this->sql);
    return true;
  }

  protected function parseCondition($field, $value = null, $join = '', $escape = true)
  {
    if (is_string($field)) {

      $operator = '';
      if (strpos($field, ' ') !== false) {
        list($field, $operator) = explode(' ', $field);
      }

      if (empty($join)) {
        $join = ($field{0} == '|') ? ' OR' : ' AND';
      }

      if ($value === null){
        $condition = '';
        if ($operator == 'null'){
          $condition = ' IS NULL ';
        }elseif ($operator == '!null'){
          $condition = ' IS NOT NULL';
        }
        return $join . ' ' . trim($field) . $condition;
      }

      if (!empty($operator)) {
        switch ($operator) {
          case '%':
            $condition = ' LIKE ';
            break;
          case '!%':
            $condition = ' NOT LIKE ';
            break;
          case '@':
            $condition = ' IN ';
            break;
          case '!@':
            $condition = ' NOT IN ';
            break;
          case '!':
            $condition = ' != ';
            break;
          default:
            $condition = $operator;
        }
      } else {
        $condition = '=';
      }

      if (is_array($value)) {
        if (strpos($operator, '@') === false) $condition = ' IN ';
        $value = '(' . implode(',', array_map(array($this, 'quote'), $value)) . ')';
      } else {
        $value = ($escape && !is_numeric($value)) ? $this->quote($value) : $value;
      }
      return $join . ' ' . str_replace('|', '', $field) . $condition . $value;
    } else if (is_array($field)) {
      $str = '';
      foreach ($field as $key => $value) {
        $str .= $this->parseCondition($key, $value, $join, $escape);
        $join = '';
      }
      return $str;
    } else {
      return false;
    }
  }

  public function sortAsc($field)
  {
    return $this->orderBy($field, 'ASC');
  }

  public function sortDesc($field)
  {
    return $this->orderBy($field, 'DESC');
  }

  public function orderBy($field, $direction = 'ASC')
  {
    $join = (empty($this->order)) ? 'ORDER BY' : ',';
    if (is_array($field)) {
      foreach ($field as $key => $value) {
        $field[$key] = $value . ' ' . $direction;
      }
    } else {
      $field .= ' ' . $direction;
    }
    $fields = (is_array($field)) ? implode(', ', $field) : $field;
    $this->order .= $join . ' ' . $fields;
    return $this;
  }

  public function build($sql, $input)
  {
    return (strlen($input) > 0) ? ($sql . ' ' . $input) : $sql;
  }

  public function quote($value)
  {
    if ($value === null) return 'NULL';

    $value = str_replace(
      array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
      array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'),
      $value
    );
    return "'$value'";
  }
}
