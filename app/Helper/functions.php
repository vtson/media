<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 29/04/2018
 * Time: 15:18
 */

if (!function_exists('view')) {
  function view($viewName, $viewData = [])
  {
    $viewName = str_replace('.', '/', $viewName);
    $viewName = 'views/' . $viewName . '.php';
    if (file_exists($viewName)) {
      foreach ($viewData as $var => $val) {
        $$var = $val;
      }
      require_once 'views/layout/master.php';
    } else {
      die('View ' . $viewName . ' not found!');
    }
    return true;
  }
}

function dd($dd)
{
  echo "<pre>" . var_dump($dd) . "</pre>";
  die;
}

if (!function_exists('loader')) {
  function loader()
  {

    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode('?', $uri);

    $uri = $uri[0];
    $uri = trim($uri, '/');
    $tmp = !empty($uri) ? explode('/', $uri) : [];
    $parameters = [];

    if (sizeof($tmp) == 0) {
      $className = 'HomeController';
      $functionName = 'index';
      $parameters = [];
    } else {
      $className = ucfirst(strtolower($tmp[0])) . 'Controller';
      $functionName = isset($tmp[1]) ? $tmp[1] : 'index';
      if (sizeof($tmp) > 2) {
        unset($tmp[0], $tmp[1]);
        $parameters = array_values($tmp);
      }
    }
    if ($className == "PostController") {
        $className = "PostsController";
        $functionName = 'show';
        $parameters[] = isset($tmp[1]) ? $tmp[1] : '';
    }

    if (!class_exists($className)) {
      $array = ['profile','setting','logout','login','create','postLogin','update','store'];
      if (in_array($tmp[0], $array)){
        $className = 'UsersController';
        $functionName = $tmp[0];
        $parameters[] = isset($tmp[1]) ? $tmp[1] : '';
      }else {
        $className = 'HomeController';
        $functionName = 'show';
        $parameters[] = $tmp[0];
      }
    }

    $class = new $className();
    if (!method_exists($class, $functionName)) die('Method ' . $functionName . ' not found in class ' . $className . ' !');

    $class->$functionName($parameters);
    return true;
  }
}

if (!function_exists('route')) {
  function route($className, $method)
  {

    if ($className == 'home' && $method == 'index') {
      $className = '';
      $method = '';
    }

    $arr = explode("/", $method);
    if ($className == 'posts' && $arr[0] == 'show'){
      $className = 'post';
      $method = $arr[1];
    }

    if ($className == 'home') {
      $tmp = explode('/', $method);
      if (sizeof($tmp) > 1) {
        list($method, $param) = $tmp;
        $className = $param;
        $method = '';
      }
    }
    if ($className == 'users') {
        $className = $method;
        $method = '';
    }

    return trim(ROOT_URL . '/' . $className . '/' . $method, '//');
  }
}


if (!function_exists('request')) {
  $globalRequest = null;
  function request()
  {
    global $globalRequest;
    if (!is_object($globalRequest)) {
      $globalRequest = new Request();
    }
    return $globalRequest;
  }
}


if (!function_exists('validator')) {
  function validator($validator)
  {
    $validator = new Validator($validator);
    return $validator;
  }
}

if (!function_exists('redirect')) {
  function redirect($url, $session = [])
  {
    $_SESSION['redirect'] = $session;
    header('Location: ' . $url);
  }
}

if (!function_exists('getFlash')) {
  function getFlash()
  {
    $flash = array_key_exists('redirect', $_SESSION) ? $_SESSION['redirect'] : [];
    $_SESSION['redirect'] = [];
    return $flash;
  }
}

if (!function_exists('asset')) {
  function asset($absLink)
  {
    return ROOT_URL . '/' . $absLink;
  }
}


if (!function_exists('__')) {
  function __($key)
  {
    return array_key_exists($key, LANGUAGE) ? LANGUAGE[$key] : $key;
  }
}
if (!function_exists('slug')) {
  function slug($name)
  {
    $slug = strtolower(str_replace(' ', '_', trim($name)));
    return $slug;
  }
}

if (!function_exists('categories')) {
  function categories()
  {
    $categoryModel= new CategoryModel();
    $categories = $categoryModel->sortDesc('created_at')
      ->select('*')
      ->getMany();

    return $categories;
  }
}

function stories(){
  $postModel = new PostModel();
  $stories = $postModel ->sortDesc('RAND()')
    ->where('type','story')
    ->select('*',10)
    ->getMany();

  return $stories;
}

function random_str($length)
{
  $possibleChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $rand = rand(0, strlen($possibleChars) - 1);
    $randomString .= substr($possibleChars, $rand, 1);
  }
  return $randomString;
}

function fileUploadType($fileUpload)
{
  $type = explode('/', $fileUpload);
  return $type;
}

function getUrl($content)
{
  preg_match("/(|http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]+(?:jpg|jpeg|png|gif|webp|mp4|webm))/i", $content, $match);
  return $match;
}

function getUrlYtb($content)
{
  preg_match("/(http|https|ftp|ftps)\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i", $content, $match);
  return $match;
}

function videoNImageTypeAllows()
{
  return [
    'video' => ['mp4', 'webm'],
    'image' => ['jpeg', 'jpg', 'png', 'gif', 'webp']
  ];
}

function classifyUrl($url)
{
  $videoNImageTypes = videoNImageTypeAllows();
  $url = getUrl($url);
  $extension = strtolower(trim(@end(explode(".", $url[0]))));
  foreach ($videoNImageTypes as $type => $format) {
    if (in_array($extension, $format)) {
      return $type;
    }
  }
  return 'youtube';
}

function isUrl($url, $type)
{
  if (filter_var($url, FILTER_VALIDATE_URL)) {
    $urlType = classifyUrl($url);
    $reUrl = !empty(getUrl($url)[0]) ? getUrl($url)[0] : getUrlYtb($url);
    switch ($urlType) {
      case 'image':
        $embedUrl = embedImage($reUrl, $reUrl, $reUrl);
        break;
      case 'video':
        $embedUrl = embedVideo($reUrl, $reUrl, $reUrl);
        break;
      case 'youtube':
        $embedUrl = embedYoutube($reUrl[0], $reUrl[2], $reUrl[0]);
        break;
      default:
        $embedUrl = '';
        break;
    }
    return  $embedUrl;
  } else {
    switch ($type) {
      case 'image':
        $embedUrl = '<figure><img src="' . asset($url) . '"></figure>';
        break;
      case 'video':
        $embedUrl = '<video src="' . asset($url) . '"></video>';
        break;
      default:
        $embedUrl = '';
        break;
    }
    return  $embedUrl;
  }
}

function embedImage($item, $link, $content)
{
  $img = str_replace($item, '<figure><img src="' . $link . '"></figure>', $content);
  return $img;
}

function embedVideo($item, $link, $content)
{
  $video = str_replace($item, '<video src="' . $link . '" >' . $link . '</video>', $content);
  return $video;
}

function embedLink($item, $link, $content)
{
  $link = str_replace($item, '<a rel="nofollow" target="_blank" href="' . $link . '">' . $link . '</a>', $content);
  return $link;
}

function embedYoutube($item, $link, $content)
{
  $ytb = str_replace($item, '<iframe src="https://www.youtube.com/embed/' . $link . '" ></iframe>', $content);
  return $ytb;
}

function paginate($limit, $number){
  $offset = ($number - 1)  * $limit;
  return $offset;
}

function current_page(){
  return ($_SESSION['current_page'] = $_SERVER['REQUEST_URI']);
}

function mergeDataPosts($posts, $postModel){
  $postData = [];
  foreach ($posts as $post){
    $totalLike = $postModel->TotalLike('post', $post->id);
    $CurrentUserLike = $postModel->CurrentUserLike('post', $post->id);

    $postData[] = (object)array_merge((array)$totalLike, (array)$CurrentUserLike, (array)$post);
  }

  return $postData;
}
