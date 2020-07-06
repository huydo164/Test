<?php
/*
* @Created by: DUYNX
* @Author    : duynx@peacesoft.net / nguyenduypt86@gmail.com
* @Date      : 08/2019
* @Version   : 1.0
*/
namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\Utility;

class DashBoardController extends BaseAdminController {

    public function __construct(){
        parent::__construct();
	}

	public function listView(){
		$messages = Utility::messages('messages');
		return view('Admin::dashboard.list',['messages'=>$messages]);
	}

}
