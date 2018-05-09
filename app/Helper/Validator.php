<?php
/**
 * Created by PhpStorm.
 * User: vtson
 * Date: 30/04/2018
 * Time: 09:18
 */

class Validator
{
  protected $validatorData;
  protected $msg;
  private $db;

  public function __construct($validatorData)
  {
    $this->db = Database::instance();
    $this->validatorData = $validatorData;
  }

  public function notEmpty($value)
  {
    return !empty($value);
  }

  public function equal($value1, $value2)
  {
    return $value1 == $value2;
  }

  public function isEmail($email)
  {
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
      return true;
    return false;
  }

  public function isFileUpload($fileUpload, $allowTypes)
  {
    $msg = '';
    $fileUpload = explode(',', $fileUpload);
    if ($fileUpload[4] > 20000000) {
      $msg .= 'Sorry, your file is too large.';
    } else {
      $type = fileUploadType($fileUpload[1]);
      $formats = videoNImageTypeAllows();
      if (in_array($type[0], $allowTypes)) {
        foreach ($formats as $key => $value) {
          if ($key == $type[0] && !in_array($type[1], $value)) {
            $msg .= ucfirst($key) . ' only support ' . implode(", ", $value) . '.<br>';
          }
        }
      } else {
        $allowTypes = str_replace(' ', ' or ', implode(' ', $allowTypes));
        $msg .= "We only support upload " . $allowTypes . '.';
      }
    }
    return $msg;
  }

  public function isUrlAllow($url)
  {
    $msg = '';
    $inputUrl = request()->get($url);
    $reUrl = filter_var($inputUrl, FILTER_VALIDATE_URL);
    if ($reUrl) {
      $match1 = getUrl($reUrl);
      $match2 = getUrlYtb($reUrl);
      if (!$match1 && !$match2) {
        $msg = "Your link is not Image or Video or Youtube Video";
      }
    } else {
      $msg = $inputUrl . " Not url.";
    }
    return $msg;
  }

  public function isExists($field)
  {
    list($table, $field) = explode('.', $field);
    $userModel = new UserModel();
    $record = $userModel->where($field, request()->get($field))
      ->select('*')
      ->getOne();
    if (array_key_exists('id', $record))
      return true;
    return false;
  }

  public function check()
  {
    $requiredFields = (array_key_exists('required', $this->validatorData)) ? $this->validatorData['required'] : [];
    $equalFields = (array_key_exists('equal', $this->validatorData)) ? $this->validatorData['equal'] : [];
    $emailFields = (array_key_exists('email', $this->validatorData)) ? $this->validatorData['email'] : [];
    $uniqueFields = (array_key_exists('unique', $this->validatorData)) ? $this->validatorData['unique'] : [];
    $validateFileUploads = (array_key_exists('validateFile', $this->validatorData)) ? $this->validatorData['validateFile'] : [];
    $validateUrls = (array_key_exists('validateUrl', $this->validatorData)) ? $this->validatorData['validateUrl'] : [];
    $msg = [];
    foreach ($requiredFields as $requiredField) {
      if (!$this->notEmpty(request()->get($requiredField)) && !empty($requiredField)) {
        $msg[] = 'Field <b>' . __($requiredField) . ' </b> is empty<br>';
      }
    }

    foreach ($emailFields as $emailField) {
      if (!$this->isEmail(request()->get($emailField))) {
        $msg[] = 'Field <b>' . $emailField . ' </b> is not an email<br>';
      }
    }

    foreach ($uniqueFields as $field) {
      if ($this->isExists($field)) {
        $msg[] = 'Field <b>' . $field . ' </b> exists, please choose another value<br>';
      }
    }

    foreach ($equalFields as $field1 => $field2) {
      if (request()->get($field1) != request()->get($field2)) {
        $msg[] = 'Field <b>' . $field1 . ' </b> not equal to <b>' . $field2 . '</b><br>';
      }
    }

    foreach ($validateFileUploads as $validateFileUpload => $fileFormat) {
      if ($this->notEmpty($this->isFileUpload($validateFileUpload, $fileFormat))) {
        $msg[] = $this->isFileUpload($validateFileUpload, $fileFormat);
      }
    }

    foreach ($validateUrls as $validateUrl) {
      if ($this->notEmpty($this->isUrlAllow($validateUrl))) {
        $msg[] = $this->isUrlAllow($validateUrl);
      }
    }

    $this->msg = $msg;
    if (sizeof($msg) > 0) return false;
    return true;
  }

  public function getMessage()
  {
    return $this->msg;
  }
}