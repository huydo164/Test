<?php
namespace App\Modules\Demo\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\SEOMeta;

class BaseDemoController extends Controller{
    public function page403(){
        $meta_img = '';
        $meta_title = $meta_keywords = $meta_description = $txt403 = CGlobal::txt403;
        SEOMeta::init($meta_img,$meta_title,$meta_keywords,$meta_description);
        return view('Demo::error.page-403', ['txt403' => $txt403]);
    }

    public function page404(){
        $meta_img = '';
        $meta_title = $meta_keywords = $meta_description = $txt404 = CGlobal::txt404;
        SEOMeta::init($meta_img, $meta_title, $meta_keywords, $meta_description);
        return view('Demo::error.page-404', ['txt404' => $txt404]);
    }
}
