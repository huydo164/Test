<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

class Upload{
	public static function uploadFile($_name='', $_file_ext='', $_max_file_size='50*1024*1024', $_folder='', $type_json=1){
		
		if($_file_ext != ''){
			$_file_ext = explode(',', $_file_ext);
		}else{
			$_file_ext=array("jpg","jpeg","png","gif");
		}
	
		if($_name!='' && isset($_FILES[$_name]) && count($_FILES[$_name])>0){
			if($_max_file_size){
				$max_file_size = $_max_file_size;
			}else{
				$max_file_size = 5 * 1024 * 1024;
			}
			$file_name = strtolower($_FILES[$_name]['name']);
			$file_tmp= $_FILES[$_name]["tmp_name"];
			$file_size = $_FILES[$_name]['size'];
			$file_type = $_FILES[$_name]['type'];
			$file_ext = @end(explode('.',$file_name));
			$ext=0;
			$name = date('h-i-s-d-m-Y',time()).'-'.self::pregReplaceString($file_name);
			$link = $name ? $name : '';
				
			if(!in_array($file_ext, $_file_ext)){
				$ext = 0;
			}else{
				$ext = 1;
			}
				
			if($file_name!='' && $ext==1 && $file_size <= $max_file_size){
				
				if($_folder != ''){
					$folder_upload = FuncLib::getRootPath().'uploads/'.$_folder;
				}else{
					$folder_upload = FuncLib::getRootPath().'uploads/';
				}
	
				if(!is_dir($folder_upload)){
					@mkdir($folder_upload,0777,true);
					chmod($folder_upload,0777);
				}
				
				if(move_uploaded_file($file_tmp, $folder_upload.'/'.$link)){
					$data = array('status' => 'Ok', 'src' => $link);
				}else{
					$data = array('status' => 'Fail', 'src' => '');
				}
				
				if($type_json){
					echo json_encode($data);exit;
				}else{
					return $link;
				}
			}
		}
	}
	//Rename File Upload
	public static function pregReplaceString($str=''){
		if(!$str) return '';
		if($str !=''){
			$str = str_replace(array('^', '$', '\\', '/', '(', ')', '|', '?', '+', '_', '*', '[', ']', '{', '}', ',', '%', '<', '>', '=', '"', '“', '”', '!', ':', ';', '&', '~', '#', '`', "'", '@' ), array(''), trim($str));
	
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
}