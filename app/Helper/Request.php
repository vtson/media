<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 09:03
 */

class Request
{
    protected $data;

    public function __construct()
    {
        foreach ($_REQUEST as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function files($key)
    {
        return array_key_exists($key, $_FILES) ? $_FILES[$key] : false;
    }

    public function get($key, $default = null)
    {
      if (isset($this->data)) {
        if (array_key_exists($key, $this->data))
          return $this->data[$key];
      }
        return $default;
    }

    public function set($key, $value = null)
    {
        $this->data[$key] = $value;
    }

    public function all()
    {
        return $this->data;
    }

    public function only($keys = [])
    {
        $newData = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $this->data)) {
                $newData[$key] = $this->data[$key];
            }
        }
        return $newData;
    }
}