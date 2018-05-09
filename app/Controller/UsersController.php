<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 15:17
 */

class UsersController extends BaseController
{
  // list, create(store), show, edit(update), delete

  public function create()
  {
    $flashMessage = getFlash();
    return view('users.create', [
      'flashMessage' => $flashMessage
    ]);
  }

  public function store()
  {
    $db = Database::instance();
    $validate = validator([
      'required' => ['username', 'password', 'email'],
      'equal' => ['password' => 're_password'],
      'email' => ['email'],
      'unique' => ['users.username', 'users.email']
    ]);

    if (!$validate->check()) {
      redirect(route('users', 'create'), ['errors' => $validate->getMessage()]);
      return;
    }
    $data = request()->all();
    $data['password'] = md5($data['password']);

    $userModel = new UserModel();
    $userModel->setData($data);
    $userModel->created_at = date('Y-m-d', time());
    $userModel->save();

    $userData = $userModel
      ->where('username', request()->get('username'))
      ->getOne();

    $userProfileModel = new ProfileModel();
    $userProfileModel->user_id = $userData->id;

    $userProfileModel->save();
    redirect('/', ['success' => ['User created!!!!!!']]);
    return;
  }

  public function login()
  {
    $flashMessage = getFlash();
    return view('users.login', [
      'flashMessage' => $flashMessage
    ]);
  }

  public function postLogin()
  {
    $validate = validator([
      'required' => ['password', 'email']
    ]);

    if (!$validate->check()) {
      redirect(route('users', 'login'), ['errors' => $validate->getMessage()]);
      return;
    }

    $userModel = new UserModel();

    $userData = $userModel->where('email', request()->get('email'))
      ->select('*')
      ->getOne();

    if (!array_key_exists('id', $userData)) {
      redirect(route('users', 'login'), ['errors' => ['User do not exists!']]);
      return;
    }

    $password = md5(request()->get('password'));
    if ($password != $userData->password) {
      redirect(route('users', 'login'), ['errors' => ['Wrong password!']]);
      return;
    }

    Auth::login($userData->id);
    $page = isset($_SESSION['current_page']) ? $_SESSION['current_page'] : '/';
    redirect($page, ['success' => ['Login successfully']]);
    return;
  }

  public function logout()
  {
    Auth::logout();
    redirect('/', ['success' => ['Logout successfully']]);
  }

  public function setting($params)
  {
    if (!Auth::checkLogin()) {
      redirect('/', ['errors' => ['Please login before do something']]);
    }

    if (Auth::user()->role === 'admin') {
      $userId = !empty($params[0]) ? $params[0] : Auth::user()->id;
    } else {
      $userId = Auth::user()->id;
    }

    $userModel = new UserModel();

    $userModel = $userModel
      ->where('id', $userId)
      ->select('*')
      ->getOne();

    if (!$userModel) {
      redirect('/', ['errors' => ['User not exists.']]);
    }

    $profile = $userModel->profile();

    $flashMessage = getFlash();

    return view('users.edit', [
      'userModel' => $userModel,
      'profile' => $profile,
      'flashMessage' => $flashMessage
    ]);
  }

  public function update()
  {
    $validate = validator([
      'required' => ['fullname', 'mobile', 'id'],
      'validateFile' => [implode(',', request()->files('avatar')) => ['image']],
    ]);
    if (!$validate->check()) {
      redirect(route('users', 'edit/' . request()->get('id')), ['errors' => $validate->getMessage()]);
      return;
    }

    $userModel = new UserModel();

    $userModel->setData(request()->all());

    $userModel->updated_at = date('Y-m-d', time());

    $userModel->save();

    $this->saveSetting();

    redirect(route('users', 'edit/' . request()->get('id')), ['success' => ['Update success!!!']]);
    return;
  }

  public function profile($params)
  {

    $username = !empty($params[0]) ? $params[0] : '';
    $userModel = new UserModel();
    $postModel = new PostModel();

    $page = !empty(request()->get('page')) ? request()->get('page') : 1;
    $page = isset($page) ? $page : 1;
    $limit = 10;
    $offset = paginate($limit,$page);

    $user = $userModel->where('username', $username)
      ->select('*')
      ->getOne();

    if (!$user) {
      redirect('/', ['error' => ['This user not exits']]);
      return;
    }

    $profile = $user->profile();

    $user = (object)array_merge((array)$profile, (array)$user);

    $count = $postModel->where('user_id', $user->id)
      ->select('count(id) as total')
      ->getOne();

    $posts = $postModel->where('user_id', $user->id)
      ->select('*', $limit, $offset)
      ->getMany();

    $posts = mergeDataPosts($posts, $postModel);

    $flashMessage = getFlash();
    return view('users.profile', [
      'user' => $user,
      'posts' => $posts,
      'flashMessage' => $flashMessage,
      'count' => $count,
      'limit' => $limit,
      'username' => $username
    ]);
  }

  private function saveSetting()
  {
    $target_dir = "storage/uploads/";
    $target_file = $target_dir . time() . '-' . basename(request()->files('avatar')["name"]);

    $profileModel = new ProfileModel();
    $profileModel = $profileModel->where('user_id', request()->get('id'))
      ->select('*')
      ->getOne();

    $profileData = request()->all();
    $profileData['id'] = $profileModel->id;

    move_uploaded_file(request()->files('avatar')["tmp_name"], $target_file);
    $profileData['avatar'] = $target_file;

    $profileModel->setData($profileData);
    $profileModel->save();
  }
}
