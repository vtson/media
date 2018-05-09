<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 01/05/2018
 * Time: 15:11
 */

class CategoryModel extends BaseModel
{
  public $fields = ['name', 'description', 'slug'];
  public $table = 'categories';
}