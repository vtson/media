<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 07/05/2018
 * Time: 23:21
 */

class LikeController extends BaseController
{
    public function action(){
        $data = request()->all();
        $likeModel = new LikeModel();
        $likeModel->setData($data);

        $objectName = isset($likeModel->object) ? $likeModel->object : '';
        if ($objectName === 'post'){
          $object_id = $likeModel->post_id;
        }elseif ($objectName === 'comment'){
          $object_id = $likeModel->comment_id;
        }else{
          return false;
        }
        $user_id = isset(Auth::user()->id) ? Auth::user()->id : '';

        $searchLike = $likeModel->search($objectName, $user_id, $object_id);

        if (!empty($searchLike)) {
          $likeModel->where('id', $searchLike->like_id)
            ->delete();
          $count = $likeModel->count($objectName, $object_id);
          echo json_encode([
            'action' => 'unlike',
            'total' => $count->like_total
          ]);
          return true;
        }

        $likeModel->user_id = $user_id;
        $likeModel->save();
        $count = $likeModel->count($objectName, $object_id);
        echo json_encode([
          'action' => 'like',
          'total' => $count->like_total
        ]);
        return true;
    }
}