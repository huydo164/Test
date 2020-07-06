<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

use Illuminate\Support\Facades\Route;

class Pagging{
    public static function getPager($numPageShow = 10, $page = 1, $total = 1, $limit = 1, $dataSearch, $page_name = 'page'){
        $total_page = ceil($total/$limit);
        if($total_page == 1) return '';
        $next = '';
        $last = '';
        $prev = '';
        $first= '';
        $left_dot  = '';
        $right_dot = '';
        $from_page = $page - $numPageShow;
        $to_page = $page + $numPageShow;
		
        if(isset($dataSearch['field_get'])){
        	unset($dataSearch['field_get']);
        }
      
        //get prev & first link
        if($page > 1){
            $prev = self::parseLink($page-1, '', "&lt; Trước", $page_name, $dataSearch);
            $first= self::parseLink(1, '', "&laquo; Đầu", $page_name, $dataSearch);
        }
        //get next & last link
        if($page < $total_page){
            $next = self::parseLink($page+1, '', "Sau &gt;", $page_name, $dataSearch);
            $last = self::parseLink($total_page, '', "Cuối &raquo;", $page_name, $dataSearch);
        }
        //get dots & from_page & to_page
        if($from_page > 0)	{
            $left_dot = ($from_page > 1) ? '<li><span>...</span></li>' : '';
        }else{
            $from_page = 1;
        }

        if($to_page < $total_page)	{
            $right_dot = '<li><span>...</span></li>';
        }else{
            $to_page = $total_page;
        }
        $pagerHtml = '';
        for($i=$from_page;$i<=$to_page;$i++){
            $pagerHtml .= self::parseLink($i, (($page == $i) ? 'active' : ''), $i, $page_name, $dataSearch);
        }
        return '<ul class="pagination">'.$first.$prev.$left_dot.$pagerHtml.$right_dot.$next.$last.'</ul>';
    }
    static function parseLink($page = 1, $class="", $title="", $page_name = 'page', $dataSearch){
        $param = $dataSearch;
        $action = Route::currentRouteAction();

        $param[$page_name] = $page;
        if($class == 'active'){
            return '<li class="'.$class.'"><a href="javascript:void(0)" title="xem trang '.$title.'">'.$title.'</a></li>';
        }
        return '<li class="'.$class.'"><a href="'.action($action, $param).'" title="xem trang '.$title.'">'.$title.'</a></li>';
    }

}
