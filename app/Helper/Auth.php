<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 10:31
 */

class Auth
{
    public static $user;

    public function __construct()
    {
    }


    public static function login($model)
    {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $model;
    }

    public static function checkLogin()
    {
        return array_key_exists('login', $_SESSION) ? $_SESSION['login'] : false;
    }

    public static function logout()
    {
        $_SESSION['login'] = false;
        session_destroy();
    }

    public static function user()
    {
        $userId = array_key_exists('user', $_SESSION) ? $_SESSION['user'] : false;
        if ($userId){
            $userModel = new UserModel();
            $userModel = $userModel->where('id', $userId)
              ->select('*')
              ->getOne();

            $profile = $userModel->profile();
            $object = (object)array_merge((array)$profile, (array)$userModel);
            return $object;
        }
    }


}