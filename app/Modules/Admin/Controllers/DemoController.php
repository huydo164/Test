<?php
namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\ValidForm;
use App\Modules\Models\Demo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class DemoController extends BaseAdminController {
    private $arrStatus = array( -1 => 'Chọn', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrFocus = array( -1 => 'Chọn' , CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
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
        $limit = 10;CGlobal::num_record_per_page;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['demo_title'] = addslashes(Request::get('demo_title' , ''));
        $search['demo_status'] = (int)Request::get('demo_status' , -1);
        $search['demo_focus'] = (int)Request::get('demo_focus' , -1);
        $search['submit'] = (int)Request::get('submit', 0);
        $search['field_get'] = '';

        $dataSearch = Demo::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search) : '';

        $optionStatus = Utility::getOption($this->arrStatus, $search['demo_status']);
        $optionFocus = Utility::getOption($this->arrFocus, $search['demo_focus']);
        $messages = Utility::messages('messages');

        return view('Admin::demo.list',[
            'data' => $dataSearch,
            'total' => $total,
            'paging' => $paging,
            'search' => $search,
            'optionStatus'  => $optionStatus,
            'optionFocus' => $optionFocus,
            'messages'  => $messages,
            'arrStatus' => $this->arrStatus
        ]);
    }
    public function getItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $data = array();
        $demo_image = '';
        $demo_image_other = array();

        if ($id > 0){
            $data = Demo::getById($id);
            if ($data != null){
                if ($data->demo_image_other != ''){
                    $demoImageOther = unserialize($data->demo_image_other);
                    if (!empty($demoImageOther)){
                        foreach ($demoImageOther as $k => $v){
                            $url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_DEMO, $id, $v, 400, 400, '', true, true);
                            $demo_image_other[] = array('img_other' => $v, 'src_img_other' => $url_thumb);
                        }
                    }
                }

                $demo_image = trim($data->demo_image);
            }
        }
        $optionStatus = Utility::getOption($this->arrStatus, isset($data['demo_status']) ? $data['demo_status'] : CGlobal::status_show);
        $optionFocus = Utility::getOption($this->arrStatus, isset($data['demo_focus']) ? $data['demo_focus'] : CGlobal::status_hide);

        return view('Admin::demo.add',[
            'id'=>$id,
            'data'=>$data,
            'optionStatus'=>$optionStatus,
            'optionFocus'=>$optionFocus,
            'news_image'=>$demo_image,
            'news_image_other'=>$demo_image_other,
            'error'=>$this->error,
        ]);
    }

    public function postItem($id = 0){
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $id_hiden = (int)Request::get('id_hiden' , 0);
        $data = array();

        $dataSave = array(
            'demo_title' => array('value' => addslashes(Request::get('demo_title')), 'require' => 1, 'messages' => 'Tiêu đề không được trống!'),
            'demo_content' => array('value' => addslashes(Request::get('demo_content')), 'require' => 1, 'messages' => 'Nội dung không được trống!'),
            'demo_created' => array('value' => time()),
            'demo_status' => array('value' => (int)Request::get('demo_status' , -1), 'require' => 0),
            'demo_focus' => array('value' => (int)Request::get('demo_focus' , -1) , 'require' => 0),
            'meta_description' => array('value' => addslashes(Request::get('meta_description')), 'require' => 0)
        );

        //Ảnh
        $image_primary = addslashes(Request::get('image_primary', ''));

        //Nhiều ảnh
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
            //Nếu không chọn ảnh chính thì ảnh đầu tiên sẽ là ảnh chính
            $dataSave['demo_image']['value'] = ($image_primary != '') ? $image_primary : $arrInputImgOther[0];
            $dataSave['demo_image_other']['value'] = serialize($arrInputImgOther);
        }
        if ($id > 0){
            unset($dataSave['demo_created']);
        }

        //validate
        $this->error = ValidForm::validInputData($dataSave);
        if ($this->error == ''){
            $id = ($id == 0) ? $id_hiden : $id;
            Demo::saveData($id, $dataSave);
            return Redirect::route('admin.demo');
        }
        else{
            foreach ($dataSave as $key => $val){
                $data[$key] = $val['value'];
            }
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['demo_status']) ? $data['demo_status'] : -1);
        $optionFocus = Utility::getOption($this->arrFocus, isset($data['demo_focus']) ? $data['demo_focus'] : CGlobal::status_hide);

        return view('Admin::demo.add',[
            'id' => $id,
            'data' => $data,
            'news_image' => $image_primary,
            'news_image_other' => $arrInputImgOther,
            'optionStatus' => $optionStatus,
            'optionFocus' => $optionFocus,
            'error'  => $this->error,
        ]);
    }

    public function delete(){
        $listId = Request::get('checkItem', array());
        $token = Request::get('_token', '');
        if (Session::token() === $token){
            if (!empty($listId) && is_array($listId)){
                foreach ($listId as $id){
                    Demo::deleteId($id);
                }
                Utility::messages('messages' , 'Xóa thành công!', 'success');
            }
        }
        return Redirect::route('admin.demo');
    }
}
