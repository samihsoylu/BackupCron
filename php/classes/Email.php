<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email {

  protected $phpMail;
  private $init, $_set = false;

  public function __construct($init) {

    # Passing true enables exception
    $this->phpMail = new PHPMailer(true);

    # Set init variable
    $this->init = $init;

    # Initialise
    $this->initialise();

  }

  /*
  * Method sets phpMail class variables, these are default variables
  * that allow the class to function.
  *
  * @return void
  */
  private function initialise() {

    # Debug settings
    //$this->phpMail->SMTPDebug = 2;
    $this->phpMail->SMTPDebug = 0;

    # Set mailer to use SMTP
    $this->phpMail->isSMTP();

    # Basic settings
    $this->phpMail->Host       = $this->init['smtp']['host'];
    $this->phpMail->SMTPAuth   = true;
    $this->phpMail->Username   = $this->init['smtp']['username'];
    $this->phpMail->Password   = $this->init['smtp']['password'];
    $this->phpMail->SMTPSecure = $this->init['smtp']['connection_type']; # tls, ssl
    $this->phpMail->Port       = $this->init['smtp']['port'];

    # From email
    $this->phpMail->setFrom($this->init['smtp']['sent_from'], $this->init['smtp']['sent_from_fullname']);

  }

  /*
  * Method initialises variables for sending emails.
  *
  * @return void
  */
  public function _set($recipient_fullname, $recipient_email, $mail_subject, $mail_body, $wordWrap = 50) {

    # Set "TO" address
    $this->phpMail->addAddress($recipient_email, $recipient_fullname);

    # Email settings
    $this->phpMail->WordWrap = $wordWrap;
    $this->phpMail->Subject  = $mail_subject;
    $this->phpMail->Body     = $mail_body;

    # Confirms that this method has been used
    $this->_set = true;

  }

  /*
  * Method sends email after checking that _set() has been used.
  *
  * @return bool
  */
  public function send() {

    # If recipient is not set
    if(!$this->_set) {

      # Throw exception is not necessary, but exists for convenience
      throw new Exception('Recipient is not set, please use _set() method.');
    }

    # Sends the email
    if(!$this->phpMail->send()) {
      throw new Exception("Error Processing Request: ". $this->phpMail->ErrorInfo);
    }

    return 1;

  }

}
