<?php

namespace Drupal\mesoestetic_ecommerce\Model;

use JsonSerializable;

class Email implements JsonSerializable {

  protected $emailTo;
  protected $userName;
  protected $email;
  protected $phone;
  protected $body;

  public function getEmailTo()
  {
    return $this->emailTo;
  }

  public function setEmailTo($emailTo)
  {
    $this->emailTo = $emailTo;
  }

  public function getUserName()
  {
    return $this->userName;
  }

  public function setUserName($userName)
  {
    $this->userName = $userName;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }

  public function getPhone()
  {
    return $this->phone;
  }

  public function setPhone($phone)
  {
    $this->phone = $phone;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function setBody($body)
  {
    $this->body = $body;
  }

  public function jsonSerialize() {
    return
      [
        'emailTo' => $this->getEmailTo(),
        'userName' => $this->getUserName(),
        'email' => $this->getEmail(),
        'phone' => $this->getPhone(),
        'text' => $this->getBody()
      ];
  }

}
