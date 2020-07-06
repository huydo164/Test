<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\ValidForm;
use App\Modules\Models\Statics;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class StaticsController extends BaseAdminController{
    private $arrStatus = array(-1 => 'Chọn', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrFocus = array(-1 => 'Chọn', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $error = '';

    public function __construct()
    {
        parent::__construct();
        Loader::loadJS('backend/js/admin.js', CGlobal::$postEnd);
        Loader::loadCSS('libs/upload/cssUpload.css', CGlobal::$postHead);
        Loader::loadJS('libs/upload/jquery.uploadfile.js', CGlobal::$postEnd);
        Loader::loadJS('backend/js/upload-admin.js', CGlobal::$postEnd);
        Loader::loadJS('backend/ace/js/bootstrap-datepicker.min.js', CGlobal::$postHead);
        Loader::loadCSS('libs/jAlert/jquery.alerts.css', CGlobal::$postHead);
        Loader::loadJS('libs/jAlert/jquery.alerts.js', CGlobal::$postEnd);
    }

    public function listView(){
        $pageNo = (int)Request::get('page', 1);
        $pageScroll = CGlobal::num_scroll_page;
        $limit = CGlobal::num_record_per_page;
        $offset = ($pageNo - 1)*$limit;
        $search = $data = array();
        $total = 0;

        $search['statics_title'] = addslashes(Request::get('statics_title', ''));
        $search['statics_status'] = (int)Request::get('statics_status', -1);
        $search['statics_focus'] = (int)Request::get('statics_focus', -1);
        $search['submit'] = (int)Request::get('submit', 0);
        $search['field_get'] = '';

        $dataSearch = Statics::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search) : '';

        $optionStatus = Utility::getOption($this->arrStatus, $search['statics_status']);
        $optionFocus = Utility::getOption($this->arrFocus, $search['statics_focus']);
        $messages = Utility::messages('messages');

        return view('Admin::statics.list',[
            'data' => $dataSearch,
            'search' => $search,
            'total' => $total,
            'paging' => $paging,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionFocus' => $optionFocus,
            'messages' => $messages
        ]);
    }

    public function getItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $data = array();
        $statics_image = '';
        $statics_image_other = array();

        if ($id > 0){
            $data = Statics::getById($id);
            if ($data != null){
                if ($data->statics_image_other != ''){
                    $staticsImageOther = unserialize($data->statics_image_other);
                    if (!empty($staticsImageOther)){
                        foreach ($staticsImageOther as $k => $v){
                            $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_STATICS, $id, $v, 400, 400, '', true, true);
                            $statics_image_other[] = array('img_other' => $v, 'src_img_other' => $url_thumb);
                        }
                    }
                }
                $statics_image = trim($data->statics_image);
            }
        }
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['statics_status']) ? $data['statics_status'] : CGlobal::status_show);
        $optionFocus = Utility::getOption($this->arrFocus, isset($data['statics_focus']) ? $data['statics_focus'] : CGlobal::status_hide);

        return view('Admin::statics.add',[
            'id' => $id,
            'data' => $data,
            'news_image' => $statics_image,
            'news_image_other' => $statics_image_other,
            'optionStatus' => $optionStatus,
            'optionFocus' => $optionFocus,
            'error' => $this->error
        ]);
    }

    public function postItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = array();

        $dataSave = array(
            'statics_title' => array('value' => addslashes(Request::get('statics_title')),'require' => 1, 'messages' => 'Tiêu đề không được trống!'),
            'statics_content' => array('value' => addslashes(Request::get('statics_content')), 'require' => 1, 'messages' => 'Nội dung không được trông!'),
            'statics_created'  => array('value' => time()),
            'statics_status' => array('value' => (int)Request::get('statics_status', -1) , 'require' => 0),
            'statics_focus' => array('value' => (int)Request::get('statics_focus', -1), 'require' => 0),
            'meta_description' => array('value' => addslashes(Request::get('meta_description')), 'require' => 0)
        );

        $image_primary = addslashes(Request::get('image_primary', ''));
        $arrInputImgOther = array();
        $getImgOther = Request::get('img_other', array());

        if (!empty($getImgOther)){
            foreach ($getImgOther as $k => $val){
                if ($val != ''){
                    $arrInputImgOther[] = $val;
                }
            }
        }
        if (!empty($arrInputImgOther) && count($arrInputImgOther) > 0){
            $dataSave['statics_image']['value'] = ($image_primary != '') ? $image_primary : $arrInputImgOther[0];
            $dataSave['statics_image_other']['value'] = serialize($arrInputImgOther);
        }

        if ($id > 0){
            unset($dataSave['statics_created']);
        }

        //Hiện lỗi
        $this->error = ValidForm::validInputData($dataSave);
        if ($this->error == ''){
            $id = ($id == 0) ? $id_hiden : $id;
            Statics::saveData($id, $dataSave);
            return Redirect::route('admin.statics');
        }
        else{
            foreach ($dataSave as $key => $val){
                $data[$key] = $val['value'];
            }
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['statics_status']) ? $data['statics_status'] : -1);
        dd($optionStatus);
        $optionFocus = Utility::getOption($this->arrFocus, isset($data['statics_focus']) ? $data['statics_focus'] : CGlobal::status_hide);

        return view('Admin.statics.add',[
            'id' => $id,
            'data' => $data,
            'news_image' => $image_primary,
            'news_image_other' => $arrInputImgOther,
            'optionStatus' => $optionStatus,
            'optionFocus' => $optionFocus,
            'error' => $this->error
        ]);
    }

    public function delete(){
        $listId = Request::get('checkItem', array());
        $toke = Request::get('_token', '');

        if (Session::token() === $toke){
            if (is_array($listId) && !empty($listId)){
                foreach ($listId as $id){
                    Statics::deleteId($id);
                }
                Utility::messages('messages', 'Xóa thành công!', 'success');
            }
        }
        return Redirect::route('admin.statics');
    }
}
