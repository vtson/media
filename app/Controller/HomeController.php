<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 15:17
 */

class HomeController extends BaseController
{

  public function index($parameters)
  {
    $page = !empty(request()->get('page')) ? request()->get('page') : 1;
    $page = isset($page) ? $page : 1;
    $limit = 10;
    $offset = paginate($limit,$page);

    $postModel = new PostModel();

    $count = $postModel
      ->where('type @',['video', 'image'])
      ->select('count(id) as total')
      ->getOne();

    $posts = $postModel ->sortDesc('created_at')
      ->where('type @',['video', 'image'])
      ->select('*', $limit, $offset)
      ->getMany();

    $posts = mergeDataPosts($posts, $postModel);

    $flashMessage = getFlash();
    return view('home.index', [
      'posts' => $posts,
      'flashMessage' => $flashMessage,
      'count' => $count,
      'limit' => $limit
    ]);
  }

  public function show($param)
  {
    $page = !empty(request()->get('page')) ? request()->get('page') : 1;
    $limit = 10;
    $offset = paginate($limit,$page);
    $postModel = new PostModel();
    $type = ['video', 'image', 'story'];

    if(in_array($param[0], $type)){
      $count = $postModel
        ->where('type', $param[0])
        ->select('count(id) as total')
        ->getOne();

      $posts =  $postModel ->sortDesc('created_at')
        ->where('type', $param[0])
        ->select('*', $limit, $offset)
        ->getMany();

    }else {
      $categoryModel = new CategoryModel();
      $category = $categoryModel->where('slug', $param[0])
        ->limit(1)
        ->select('*')->getOne();

      if (!$category){
        $param = ucfirst($param[0]);
        redirect('/', ['errors' => ['Session of <b>'.$param.'</b> not exists!!!!']]);
        return;
      }

      $category_id = $category->id;

      $count = $postModel
        ->where('category_id', $category_id)
        ->where('type !','story')
        ->select('count(id) as total')
        ->getOne();

      $posts = $postModel->sortDesc('created_at')
        ->where('category_id', $category_id)
        ->where('type !','story')
        ->select('*', $limit, $offset)
        ->getMany();
    }

    $posts = mergeDataPosts($posts, $postModel);

    if (!$posts){
      $param = ucfirst($param[0]);
      redirect('/', ['errors' => ['Post of <b>'.$param.'</b> not exists!!!!']]);
      return;
    }

    $flashMessage = getFlash();
    return view('home.show', [
      'posts' => $posts,
      'flashMessage' => $flashMessage,
      'count' => $count,
      'limit' => $limit,
      'pageName' => $param[0]
    ]);
  }
}
