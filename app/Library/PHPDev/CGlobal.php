<?php
/*
* @Created by: DUYNX
* @Author    : nguyenduypt86@gmail.com
* @Date      : 06/2016
* @Version   : 1.0
*/
namespace App\Library\PHPDev;

class CGlobal{
    //Dev
    const is_dev = 0;

    static $cssVer = 1.0;
    static $jsVer = 1.0;

    //Position Header, Footer
    public static $postHead = 1;
    public static $postEnd = 2;

    //Add CSS, JS, Meta
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';
    public static $extraMeta = '';

    //Role
    const rid_admin = 1;
    const rid_manager = 2;
    const rid_member = 3;

    const domain = 'Admin';
    const nameSite = 'Admin';
    const phoneSupport = '0943.886.786';
    const emailAdmin = 'hotro@phongthuyhoanggia.edu.vn';
    const emailManager = 'hotro@phongthuyhoanggia.edu.vn';
    const emailSupport = 'hotro@phongthuyhoanggia.edu.vn';

    const txt403 = 'Rất tiếc. Bạn không thể truy cập trang này....';
    const txt404 = 'Rất tiếc. Chúng tôi không thể tìm thấy trang này...';
    const txtMaintain = 'Website đang bảo trì...';

    const num_record_per_page = 30;
    const num_scroll_page = 2;
    const num_record_per_page_news = 12;
    const num_record_same_news = 5;

    const status_default = -1;
    const status_hide = 0;
    const status_show = 1;

    //Size Img
    public static $arrSizeImg = array(
        '2'=>'200x200',
        '4'=>'400x400',
        '6'=>'600x600',
        '8'=>'800x800',
    );

    //Folder
    const IMAGE_ERROR = -5;
    const FOLDER_PRODUCT = 'product';
    const FOLDER_PHOTO = 'photo';
    const FOLDER_PHONE = 'phone';
    const FOLDER_DEMO = 'demo';
    const FOLDER_STATICS = 'statics';

    //Api Key Facebook
    const facebook_app_id = '';
    const facebook_app_secret = '';
    const facebook_default_graph_version = 'v2.8';
    const facebook_persistent_data_handler = 'session';
    //Api Key Google
    const googe_client_id = '';
    const googe_client_secret = '';

    //Cards
    const status_int_0 = 0;
    const status_int_1 = 1;
    const status_int_2 = 2;

    //Trang thai khoa or hoat dong
    public static $arrStatus = array(
        self::status_int_0=>'Khóa',
        self::status_int_1=>'Hoạt động',
    );

    public static $arrTime = array(
        '1'=>'Tý (23h-1h)',
        '2'=>'Sửu (1h-3h)',
        '3'=>'Dần (3h-5h)',
        '4'=>'Mão (5h-7h)',
        '5'=>'Thìn (7h-9h)',
        '6'=>'Tỵ (9h-11h)',
        '7'=>'Ngọ (11h-13h)',
        '8'=>'Mùi (13h-15h)',
        '9'=>'Thân (15h-17h)',
        '10'=>'Dậu (17h-19h)',
        '11'=>'Tuất (19h-21h)',
        '12'=>'Hợi (21h-23h)'
    );
}
