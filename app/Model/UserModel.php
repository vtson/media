<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 16:38
 */

class UserModel extends BaseModel
{

    public $fields = ["user_id","username", "password", "email", "role", "fullname", "created_at", "modified_at", "deleted_at"];
    public $table = 'users';

    public function profile()
    {
        $profileModel = new ProfileModel();

        $profileModel = $profileModel->where('user_id', $this->id)
          ->select('*')
          ->getOne();

        return $profileModel;
    }

    public function posts()
    {
      $postModel = new PostModel();
      $postModel = $postModel->where('user_id', $this->id)
        ->select('*')
        ->getMany();

      return $postModel;
    }
}
