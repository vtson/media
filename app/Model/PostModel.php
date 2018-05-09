<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 15:35
 */

class PostModel extends BaseModel
{
  protected $userModel;
  protected $commentModel;
  protected $profileModel;
  protected $likeModel;
  protected $db;

  public $fields = ['title', 'type', 'slug', 'story', 'user_id', 'video', 'total', 'image', 'category_id', 'thumbnail', 'tags'];
  public $table = 'posts';

  public function __construct()
  {
    parent::__construct();
    $this->userModel = new UserModel();
    $this->commentModel = new CommentModel();
    $this->profileModel = new ProfileModel();
    $this->likeModel = new LikeModel();
  }

  public function user($id)
  {
    $this->userModel->where = null;
    $userModel = $this->userModel->where('id', $id)
      ->select('*')
      ->getOne();

    $profile = $userModel->profile();


    $object = (object)array_merge((array)$userModel, (array)$profile);

    return $object;
  }

  public function CurrentUserLike($object, $object_id){
    $this->likeModel->where = null;
    $id = isset(Auth::user()->id) ? Auth::user()->id : '';
    $like = $this->likeModel->search($object, $id, $object_id);
    return $like;
  }

  public function TotalLike($object, $object_id){
    $this->likeModel->where = null;
    $totalLike = $this->likeModel->count($object, $object_id);
    return $totalLike;
  }

  public function ParentComments()
  {

    $parentComments = $this->commentModel->where('post_id', $this->id)
      ->where('parent_id null')
      ->select('*')
      ->getMany();

    $parents = $this->userRelation($parentComments);

    return $parents;
  }

  public function ChildComments()
  {
    $this->commentModel->where = null;

    $childComments = $this->commentModel->where('post_id', $this->id)
      ->where('parent_id !null')
      ->select('*')
      ->getMany();

    $childs = $this->userRelation($childComments);

    return $childs;
  }

  private function userRelation($relationDatas)
  {
    $object = [];

    foreach ($relationDatas as $relationData) {

      $users = $this->user($relationData->user_id);

      $likes = $this->CurrentUserLike('comment', $relationData->id);

      $totalLike = $this->TotalLike('comment', $relationData->id);

      $object[] = (object)array_merge((array)$users, (array)$likes, (array)$totalLike, (array)$relationData);
    }

    return $object;
  }
}