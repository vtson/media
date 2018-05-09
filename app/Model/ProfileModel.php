<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 14:06
 */

class ProfileModel extends BaseModel
{
    public $fields = ['user_id', 'mobile', 'email', 'avatar', 'address', 'bod'];
    public $table = 'profiles';
}