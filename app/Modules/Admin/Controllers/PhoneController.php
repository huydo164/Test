<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\ValidForm;
use App\Modules\Models\Phone;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class PhoneController extends BaseAdminController {
    private $arrStatus = array(-1 => 'Chọn', CGlobal::status_show => 'Hiện', CGlobal::status_hide => 'Ẩn');
    private $arrFocus = array(-1 => 'Chọn', CGlobal::status_show => 'Hiện' , CGlobal::status_hide => 'Ẩn');
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
        $pageNo = (int)Request::get('page' , 1);
        $pageScroll = CGlobal::num_scroll_page;
        $limit = CGlobal::num_record_per_page;
        $offset = ($pageNo - 1)*$limit;
        $search = $data = array();
        $total = 0;

        $search['phone_title'] = addslashes(Request::get('phone_title', ''));
        $search['phone_status'] = (int)Request::get('phone_status', -1);
        $search['phone_focus'] = (int)Request::get('phone_focus', -1);
        $search['submit'] = (int)Request::get('submit', 0);
        $search['field_get'] = '';

        $dataSearch = Phone::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo,$total, $limit, $search) : '';

        $optionStatus = Utility::getOption($this->arrStatus, $search['phone_status']);
        $optionFocus = Utility::getOption($this->arrFocus, $search['phone_focus']);
        $messages  = Utility::messages('messages');

        return view('Admin::phone.list',[
            'data' => $dataSearch,
            'paging' => $paging,
            'total' => $total,
            'search' => $search,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionFocus' => $optionFocus,
            'messages' => $messages
        ]);
    }
    public function getItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $data = array();
        $phone_image = '';
        $phone_image_other = array();

        if ($id > 0){
            $data = Phone::getById($id);
            if ($data != null){
                if ($data->phone_image_other != ''){
                    $phoneImageOther = unserialize($data->phone_image_other);
                    if (!empty($phoneImageOther)){
                        foreach ($phoneImageOther as $k=> $v){
                            $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_PHONE, $id, $v, 400, 400, '', true, true);
                            $phone_image_other[] = array('img_other' => $v, 'src_img_other' => $url_thumb);
                        }
                    }
                }
                //Ảnh
                $phone_image = trim($data->phone_image);
            }
        }
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['phone_status']) ? $data['phone_status'] : CGlobal::status_show);
        $optionFocus = Utility::getOption($this->arrFocus, isset($data['phone_focus']) ? $data['phone_focus'] : CGlobal::status_hide);

        return view('Admin::phone.add',[
            'id'=>$id,
            'data'=>$data,
            'optionStatus'=>$optionStatus,
            'optionFocus'=>$optionFocus,
            'news_image'=>$phone_image,
            'news_image_other'=>$phone_image_other,
            'error'=>$this->error,
        ]);
    }
    public function postItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = array();

        $dataSave  = array(
            'phone_title' => array('value' => addslashes(Request::get('phone_title')), 'require' => 1, 'messages' => 'Tiêu đề không được trống!'),
            'phone_content' => array('value' => addslashes(Request::get('phone_content')), 'require' => 0 ),
            'phone_created' => array('value' => time()),
            'phone_status' => array('value' => (int)Request::get('phone_status', -1), 'require' => 0),
            'phone_focus' => array('value'=> (int)Request::get('phone_focus' , -1), 'require' => 0),
            'meta_description' => array('value' => addslashes(Request::get('meta_description')), 'require' => 0)
        );

        //Ảnh
        $image_primary = addslashes(Request::get('image_primary', ''));
        //Img Other
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
            //nếu không chọn ảnh chính thì tự động ảnh đầu tiên sẽ là ảnh chính
            $dataSave['phone_image']['value'] = ($image_primary != '') ? $image_primary : $arrInputImgOther[0];
            $dataSave['phone_image_other']['value'] = serialize($arrInputImgOther);
        }
        if ($id > 0){
            unset($dataSave['phone_created']);
        }
        $this->error = ValidForm::validInputData($dataSave);
        if ($this->error == ''){
            $id = ($id == 0) ? $id_hiden : $id;
            Phone::saveData($id, $dataSave);
            return Redirect::route('admin.phone');
        }
        else{
            foreach ($dataSave as $key=>$val){
                $data[$key] = $val['value'];
            }
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['phone_status']) ? $data['phone_status'] : -1);
        $optionFocus = Utility::getOption($this->arrFocus, isset($data['phone_focus'])? $data['phone_focus'] : CGlobal::status_hide);

        return view('Admin::phone.add',[
            'id'=>$id,
            'data'=>$data,
            'optionStatus'=>$optionStatus,
            'optionFocus'=>$optionFocus,
            'news_image'=>$image_primary,
            'news_image_other'=>$arrInputImgOther,
            'error'=>$this->error,
        ]);
    }

    public function delete(){
        $listId = Request::get('checkItem', array());
        $token = Request::get('_token', '');
        if (Session::token() === $token){
            if (!empty($listId) && is_array($listId)){
                foreach ($listId as $id){
                    Phone::deleteId($id);
                }
                Utility::messages('messages' , 'Xóa thành công!', 'success');
            }
        }
        return Redirect::route('admin.phone');
    }
}
