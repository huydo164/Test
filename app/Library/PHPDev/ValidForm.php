<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

class ValidForm{
	//check regex email
	public static function checkRegexEmail($str=''){
		if($str != ''){
			$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
			if (!preg_match($regex, $str)){
		    	return false;
			}
			return true;
		}
		return false;
	}
	//check regex url
	public static function checkRegexUrl($str=''){
		if($str != ''){
			$regex = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i';
			if (!preg_match($regex, $str)){
		    	return false;
			}
			return true;
		}
		return false;
	}
	//check regix name login
	public static function checkRegexName($str=''){
		if($str != ''){
			$regex = '/^[a-zA-Z0-9_@]*$/';
			if (!preg_match($regex, $str)){
		    	return false;
			}
			return true;
		}
		return false;
	}
	//check regix name login
	public static function checkRegexPass($str='', $length=6){
		if($str != '' && $length > 0){
			if(strlen($str) < $length){
				return false;
			}else{
				$regex = '/^[a-zA-Z0-9_@&#%=~,;\{\}\(\)\^\$\.\+\*\?\/\ ]*$/';
				if (!preg_match($regex, $str)){
			    	return false;
				}
				return true;
			}
		}
		return false;
	}
	//check phone number
	public static function checkRegexPhone($str=''){
		if($str != ''){
			$regex = '/^[0-9() -]+$/';
			if (!preg_match($regex, $str)){
		    	return false;
			}
			return true;
		}
		return false;
	}

	public static function isMobile($str=''){
		if($str != ''){
			//$regex = '/^0[198]\d{8,10}$/';
			$regex = '/^0[98735]\d{8,10}$/';
			if(!preg_match($regex, $str)){
				return false;
			}
			return true;
		}
		return false;
	}
	public static function isPhoneNumber($str=''){
		if($str != ''){
			$regex = '/^\d{7,15}$/';
			if(!preg_match($regex, $str)){
				return false;
			}
			return true;
		}
		return false;
	}
	//trim, stripslashes, htmlspecialchars string
	public static function input($str='') {
		$str = trim($str);
		$str = stripslashes($str);
		$str = htmlspecialchars($str);
		return $str;
	}

	public static function validInputData($dataInput){
		$errors = array();
		$message = '';
		foreach($dataInput as $k=>$v){
			if(isset($v['require']) && $v['require'] == 1){
				if(isset($v['value']) && ($v['value'] == '' || $v['value'] == -1)){
					$errors[] = $v['messages'];
				}
			}
		}
		//Build errors to string report 
		if(!empty($errors)){
			foreach($errors as $msg){
				$message .= $msg.'<br/>';
			}
		}
		return $message;
	}
}