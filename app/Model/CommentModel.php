<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 06/05/2018
 * Time: 15:59
 */

class CommentModel extends BaseModel
{

  public $fields = ['comment_id', 'user_id', 'post_id', 'parent_id', 'content', 'to_user_id', 'created_at', 'modified_at', 'deleted_at'];
  public $table = 'comments';

}