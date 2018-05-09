<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 05/05/2018
 * Time: 01:01
 */

class AjaxController extends BaseController
{
  public function getPosts()
  {
    $start = request()->get('start');
    $limit = request()->get('limit');

    return compact('start', 'limit');
  }
}