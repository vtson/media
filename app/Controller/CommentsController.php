<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 07/05/2018
 * Time: 07:36
 */

class CommentsController extends BaseController
{
  public function store(){
    $target_dir = "views/comments/comment.php";
    $media = file_get_contents($target_dir);

    if (!Auth::checkLogin()) {
      echo json_encode([
        'errorLogin' => 'Please login to post a comment'
      ]);
      return;
    }

    $data = request()->all();


    if (!$data['content']){
      echo json_encode([
        'errorComment' => 'Comment is not be empty'
      ]);
      return;
    }
    $content = $data['content'];
    $user_id = Auth::user()->id;

    $avatar = asset(Auth::user()->avatar);
    $username = Auth::user()->username;

    $commentModel = new CommentModel();

    $commentModel->setData($data);
    $commentModel->user_id = $user_id;
    $commentModel->created_at = date('Y-m-d', time());

    $commentModel->save();

    $media = str_replace("{{username}}", $username, $media);
    $media = str_replace("{{avatar}}", $avatar, $media);
    $media = str_replace("{{content}}", $content, $media);

    echo json_encode($media);
  }
}