<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 07/05/2018
 * Time: 23:20
 */

class LikeModel extends BaseModel
{

  public $fields = ['like_id', 'like_total', 'user_id', 'object', 'post_id', 'comment_id', 'created_at', 'modified_at', 'deleted_at'];
  public $table = 'likes';

  public function __construct()
  {
    parent::__construct();
  }

  public function search($objectName, $user_id, $object_id){

    $result = $this->where('user_id', $user_id)
      ->where('object', $objectName)
      ->where($objectName."_id", $object_id)
      ->select('id as like_id')
      ->getOne();

    return $result;
  }

  public function count($objectName, $object_id){
    $this->where = null;
    $result = $this->where('object', $objectName)
      ->where($objectName."_id", $object_id)
      ->select('count(id) as like_total')
      ->getOne();

    return $result;
  }
}