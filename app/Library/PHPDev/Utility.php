<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

use Illuminate\Support\Facades\Session;

class Utility{
	
	//Set nofollow tag a
	static function setNofollow($str){
		return preg_replace('/(<a.*?)(rel=[\"|\'].*?[\"|\'])?(.*?\/a>)/i', '$1 rel="nofollow" $3', $str);
	}
	//Check file swf - flash
	static function chkFileExtension($str='') {
		$match= preg_match('/.swf/i', $str);
		if($match>0){
			return "yes";
		}else{
			return "no";
		}
	}
	//Cut string
	static function substring($str, $length = 100, $replacer='...'){
		$str = strip_tags($str);
		if(strlen($str) <= $length){
			return $str;
		}
		$str = trim(@substr($str,0,$length));
		$posSpace = strrpos($str,' ');
		$replacer="...";
		return substr($str,0,$posSpace).$replacer;
	}
	//Cut html
	function cut_link_html($str=''){
		$match= preg_match('/.html/i', $str);
		if($match > 0){
			if(substr($str, -5)=='.html'){
				$str = substr($str, 0, -5);
			}
		}
		return $str;
	}
	//Cut word
	static function cutWord($str, $num, $replacer = '...') {
		$arr_str = explode(' ', $str);
		$count = count($arr_str);
		$arr_str = array_slice($arr_str, 0, $num);
		$res = implode(' ', $arr_str);
		if ($count > $num) {
			if($replacer != ''){
				$res .= $replacer;
			}
		}
		return $res;
	}
	//Check header
	public static function headerReferer(){
		header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Max-Age: 1000');
		header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		header("Content-Type: text/html; charset=utf-8");
	}
	public static function pageNotRound(){
		header("HTTP/1.0 404 Not Found");
		echo "Not Found";
		die;
	}
	public static function pageAccessDenied(){
		header("HTTP/1.0 403 Forbidden");
		echo "Forbidden";
		die;
	}
	//Replace ->"<- to ->'<-
	function chage_text($str=''){
		if($str=='') return '';
		if($str !=''){
			$str = preg_replace('/"/',"'",$str);
			return $str;
		}
	}
	//Convert alias
	public static function pregReplaceStringAlias($str=''){
		if(!$str) return '';
		if($str !=''){
			$str = str_replace(array('^', '$', '\\', '/', '(', ')', '|', '?', '+', '_', '*', '[', ']', '{', '}', ',', '.', '%', '<', '>', '=', '"', '“', '”', '!', ':', ';', '&', '~', '#', '`', "'", '@' ), array(''), html_entity_decode(trim($str)));
	
			$unicode = array(
					'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
					'd'=>'đ',
					'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
					'i'=>'í|ì|ỉ|ĩ|ị',
					'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
					'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
					'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
					'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
					'D'=>'Đ',
					'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
					'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
					'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
					'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
					'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			);
			foreach($unicode as $nonUnicode=>$uni){
				$str = preg_replace("/($uni)/i", $nonUnicode, $str);
			}
	
			$str = preg_replace("/\s+/","-",$str);
			$str = preg_replace("/\-+/","-",$str);
	
			return strtolower($str);
		}
	}
	//Get IP
	public static function getClientIp() {
		$ip = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ip = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = '';
		return $ip;
	}
	//Get OS
	public static function getOS(){
		$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		$os_platform   =   "Unknown OS Platform";
		$os_array      =   array(
			'/windows nt 10/i'      =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile',
			'/windows phone os/i'   =>  'WindowsPhone',
		);
	
		foreach ($os_array as $regex => $value) {
			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}
		}
		
		return $os_platform;
	}
	//Build option
	static function getOption($options_array, $selected, $disabled = array()) {
		$input = '';
		if ($options_array)
			foreach ($options_array as $key => $text) {
				$input .= '<option value="' . $key . '"';
				if (!in_array($selected, $disabled)) {
					if ($key === '' && $selected === '') {
						$input .= ' selected';
					} else
						if ($selected !== '' && $key == $selected) {
							$input .= ' selected';
						}
				}
				if (!empty($disabled)) {
					if (in_array($key, $disabled)) {
						$input .= ' disabled';
					}
				}
				$input .= '>' . $text . '</option>';
			}
		return $input;
	}
	//Build option Muti
	static function getOptionMultil($options_array, $arrSelected) {
		$input = '';
		if ($options_array)
			foreach ($options_array as $key => $text) {
				$input .= '<option value="' . $key . '"';
				if ($key === '' && empty($arrSelected)) {
					$input .= ' selected';
				} else
					if (!empty($arrSelected) && in_array($key, $arrSelected)) {
						$input .= ' selected';
					}
				$input .= '>' . $text . '</option>';
			}
		return $input;
	}
	//Number format
	static function numberFormat($number = 0) {
		if ($number >= 1000) {
			return number_format($number, 0, ',', '.');
		}
		return $number;
	}
	//Strip tags script, style
	static function strip_html_tags($string){
		return preg_replace(array('/\<(script)(.+)>/i', '/\<(.+)(script)>/i', '/\<(style)(.+)>/i', '/\<(.+)(style)>/i'), '', $string);
	}
	/*
	base 64 string
	$start_add_str: so ky tu dau them vao
	$end_add_str: so ky tu cuoi them vao
	*/
	public static function base64EncodeStr($str='', $start_add_str='', $end_add_str=''){
		if($str != ''){
			if($start_add_str != ''){
				$str .= $start_add_str;
			}
			if($end_add_str != ''){
				$str .= $end_add_str;
			}
			return base64_encode($str);
		}
		return '';
	}
	/*
	base 64 string
	$start_cut_str: so ky tu dau cat bo
	$end_add_str: so ky tu cuoi cat bo
	*/
	public static function base64DecodeStr($str='', $start_cut_str=0, $end_cut_str=0){
		if($str != ''){
			$str = base64_decode($str);
			if($start_cut_str > 0){
				$str = substr($str, $start_cut_str);
			}
			if($end_cut_str < 0 && strlen($str) > abs($end_cut_str)){
				$str = substr($str, 0, $end_cut_str);
			}
			return intval($str);
		}
		return '';
	}
	/*
	 * Cut string
	* $start_cut_str: so ky tu dau cat bo
	* $end_add_str: so ky tu cuoi cat bo
	*/
	public static function cutStr($str='', $start_cut_str=0, $end_cut_str=0){
		if($str != ''){
			if($start_cut_str > 0){
				$str = substr($str, $start_cut_str);
			}
			if($end_cut_str < 0 && strlen($str) > abs($end_cut_str)){
				$str = substr($str, 0, $end_cut_str);
			}
			return intval($str);
		}
		return '';
	}
	//Show messages
	public static function messages($alert, $messages='', $type='success'){
		$str = '';
		if(session()->has($alert)){
			$str = session()->get($alert);
		}
		//refreshed
		$refreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		if($refreshed){
			if(session()->has($alert)){
				session()->forget($alert);
			}
		}else{
			if($messages != ''){
				if($type == 'success'){
					$messages = '<div class="alert-admin alert alert-success">'.$messages.'</div>';
				}elseif($type == 'error'){
					$messages = '<div class="alert-admin alert alert-danger">'.$messages.'</div>';
				}
			}
			session()->put($alert, $messages);
		}
		return $str;
	}
	public static function checkDomain(){
		$domain = CGlobal::domain;
		$base_url = Config::get('config.BASE_URL');
		if(!preg_match('/'.$domain.'/', $base_url, $Matches)){
			die('Bạn không có quyền truy cập!');
		}
	}
	public static function randomString($length=5){
		$str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$strLength = strlen($str);
		$random_string = '';
		for($i=0; $i<=$length; $i++) {
			$random_string .= $str[rand(0, $strLength - 1)];
		}
		return $random_string;
	}
	public static function strReplace($text='', $strInput='', $strReplace=''){
		if($text !='' && $strInput != ''){
			$text = str_replace($strInput, $strReplace, $text);
		}
		return $text;
	}
	public static function arrByField($data=array(), $fieldName='', $fieldVal=''){
		$result = array();
		if(sizeof($data) > 0 && $fieldName != '' && $fieldVal != ''){
			foreach($data as $v) {
				if(isset($v->$fieldVal) && isset($v->$fieldVal)){
					$result[$v->$fieldVal] = $v->$fieldName;
				}
			}
		}
		return $result;
	}
	public static function forgetSession($keySession=''){
		if($keySession != ''){
			if(Session::has($keySession)){
				Session::forget($keySession);
			}
		}
	}
}
