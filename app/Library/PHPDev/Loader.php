<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

class Loader{
	//Load css to header or footer
	public static function loadCSS($file_name, $position=1){
        if(is_array($file_name)){
			foreach($file_name as $v){
				Loader::loadCSS($v);
			}
			return;
		}
		if(strpos($file_name, 'http://') !== false){
			$html = '<link rel="stylesheet" href="' . $file_name . ((CGlobal::$cssVer) ? '?ver=' . CGlobal::$cssVer : '') . '" type="text/css">' . "\n";
			if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderCSS, $html) === false)
				CGlobal::$extraHeaderCSS .= $html . "\n";
			elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterCSS, $html) === false)
				CGlobal::$extraFooterCSS .= $html . "\n";
		}else{
			$html = '<link type="text/css" rel="stylesheet" href="' . FuncLib::getBaseUrl() . 'assets/' . $file_name . ((CGlobal::$cssVer) ? '?ver=' . CGlobal::$cssVer : '') . '" />' . "\n";
			if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderCSS, $html) === false)
				CGlobal::$extraHeaderCSS .= $html . "\n";
			elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterCSS, $html) === false)
				CGlobal::$extraFooterCSS .= $html . "\n";
		}
	}
	//Load js to header or footer
	public static function loadJS($file_name, $position=1){
		if(is_array($file_name)){
			foreach($file_name as $v){
				Loader::loadJS($v);
			}
			return;
		}
		
		if(strpos($file_name, 'http://') !== false){
			$html = '<script type="text/javascript" src="' . $file_name . ((CGlobal::$jsVer) ? '?ver=' . CGlobal::$jsVer : '') . '"></script>';
			if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderJS, $html) === false)
				CGlobal::$extraHeaderJS .= $html . "\n";
			elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterJS, $html) === false)
				CGlobal::$extraFooterJS .= $html . "\n";
		}else{
			$html = '<script type="text/javascript" src="' . FuncLib::getBaseUrl() . 'assets/' . $file_name . ((CGlobal::$jsVer) ? '?ver=' . CGlobal::$jsVer : '') . '"></script>';
			if ($position == CGlobal::$postHead && strpos(CGlobal::$extraHeaderJS, $html) === false)
				CGlobal::$extraHeaderJS .= $html . "\n";
			elseif ($position == CGlobal::$postEnd && strpos(CGlobal::$extraFooterJS, $html) === false)
				CGlobal::$extraFooterJS .= $html . "\n";
		}
	}
}