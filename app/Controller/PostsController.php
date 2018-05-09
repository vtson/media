<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 14:06
 */

class PostsController extends BaseController
{
  public function create($parameters)
  {
    $flashMessage = getFlash();
    return view('posts.create', [
      'flashMessage' => $flashMessage
    ]);
  }

  public function store($parameters)
  {
    $db = Database::instance();
    $typeNContent = [];
    $fieldStory = '';

    if (!empty($_FILES) && request()->files('postUpload') !== null){
      $validKey= 'validateFile';
      $validValue = [implode(',', request()->files('postUpload')) => ['image', 'video']];
      $typeNContent = $this->moveFile();
    }elseif(request()->get('postUrl') !== null){
      $validKey = 'validateUrl';
      $validValue = ['postUrl'];
      $reUrl1 = getUrl(request()->get('postUrl'));
      $reUrl2 = getUrlYtb(request()->get('postUrl'));
      $url = !empty($reUrl1) ? $reUrl1[0] : $reUrl2[0];
      $typeNContent['content'] = $url;
      $typeNContent['type'] = classifyUrl($url);
      if ($typeNContent['type'] == 'youtube'){
        $typeNContent['type'] = 'video';
      }
    }elseif(request()->get('postStory') !== null){
      $typeNContent['type'] = 'story';
      $typeNContent['content'] = request()->get('postStory');
      $validKey = '';
      $validValue = '';
      $fieldStory = 'postStory';
    }

    $validate = validator([
      'required' => ['title', 'category_id', $fieldStory],
      $validKey => $validValue
    ]);
    if (!$validate->check()) {
      redirect(route('posts', 'create'), ['errors' => $validate->getMessage()]);
      return;
    }
    $_SESSION['post'] = true;
    $data = request()->all();
    $postModel = new PostModel();
    $postModel->setData($data);
    $postModel->created_at = date('Y-m-d', time());

    $user_id = Auth::user()->id;
    $postModel->user_id = $user_id;
    $postModel->slug = random_str(9) . "_" . $user_id;

    $this->addPostType($postModel, $typeNContent['type'], $typeNContent['content']);

    $postModel->save();

    redirect('/', ['success' => ['Post created!!!!!!']]);
    return;
  }

  public function show($params)
  {

    current_page();
    $postModel = new PostModel();

    $postData = $postModel->where('slug', $params[0])
      ->select('*')
      ->getOne();

    if (!$postData){
      redirect('/', ['error' => ['Post not found']]);
      return;
    }

    $user = $postData->user($postData->user_id);

    $CurrentUserLike = $postData->CurrentUserLike('post', $postData->id);

    $totalLike = $postData->TotalLike('post', $postData->id);

    $post = (object)array_merge((array)$user, (array) $totalLike, (array)$CurrentUserLike, (array)$postData);

    $parentComments = $postData->ParentComments();

    $childComments = $postData->ChildComments();

    if (!$postData){
      redirect('/', ['errors' => ['Post not exists!!!!']]);
      return;
    }

    $flashMessage = getFlash();
    return view('posts.show', [
      'post' => $post,
      'parentComments' => $parentComments,
      'childComments' => $childComments,
      'flashMessage' => $flashMessage
    ]);
  }

  private function moveFile(){
    $type = fileUploadType(request()->files('postUpload')['type']);
    $target_dir = "storage/uploads/post";
    $target_file = $target_dir . '/' . time() . '-' . basename(request()->files('postUpload')["name"]);
    move_uploaded_file(request()->files('postUpload')["tmp_name"], $target_file);
    $typeNContent['type'] = $type[0];
    $typeNContent['content'] = $target_file;
    return $typeNContent;
  }

  private function addPostType($postModel, $type, $content)
  {
    $postModel->type = $type;
    $postModel->$type = $content;
    return $postModel;
  }
}

