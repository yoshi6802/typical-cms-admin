<?php
require_once( APP_ROOT_PATH.DS.'dba'.DS.'common.php');

abstract class app_controller{

	public $message;
	public $error;

	public function before_filter(){
		$this->message = $this->getFlash();
	}

	public function setFlash($message){
		$_SESSION['_message'] = $message;
	}

	public function getFlash(){
		$message = App::session('_message');
		unset($_SESSION['_message']);
		return $message;
	}
}
