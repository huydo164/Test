<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\ValidForm;
use App\Modules\Models\User;
use App\Modules\Models\UserRoleGroup;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class UserController extends BaseAdminController{
    private $arrStatus = array(-1 => '--Chọn trạng thái--', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $arrRoleGroup = [];
    private $error = '';

    public function __construct()
    {
        parent::__construct();
        Loader::loadJS('backend/js/admin.js', CGlobal::$postEnd);
        Loader::loadCSS('libs/jAlert/jquery.alerts.css', CGlobal::$postHead);
        Loader::loadJS('libs/jAlert/jquery.alerts.js', CGlobal::$postEnd);

        $dataSearchRoleGroup['field_get'] = 'group_role_id,group_role_title';//gán giá trị trường group_role_id và title vào dataSearchRoleGroup
        $listRoleGroup = UserRoleGroup::getAllRoleGroup($dataSearchRoleGroup);//Lấy danh sách nhóm quyền
        $this->arrRoleGroup = Utility::arrByField($listRoleGroup, 'group_role_title','group_role_id');//Hiển thị danh sách nhóm quyền
    }

    public function listView(){
        $pageNo = (int)Request::get('page', 1);
        $pageScroll = CGlobal::num_scroll_page;
        $limit  = CGlobal::num_record_per_page;
        $offset = ($pageNo - 1)*$limit;
        $search = $data = array();
        $total = 0;

        $search['user_rid'] = (int)Request::get('user_rid', -1);
        $search['user_name'] = addslashes(Request::get('user_name', ''));
        $search['user_status'] = (int)Request::get('user_status', -1);
        $search['submit'] = (int)Request::get('submit', 0);
        $search['field_get'] = '';

        //session_user sẽ check xem acc login vào là admin hay nhân viên
        $session_user = User::userLogin();
        if ($session_user['user_rid'] != CGlobal::rid_admin && $session_user['user_rid'] != CGlobal::rid_teacher){
            $search['user_rid'] = isset($session_user['user_rid']) ? $session_user['user_rid'] : -1;
        }

        $dataSearch = User::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search) : '';

        $optionStatus = Utility::getOption($this->arrStatus, $search['user_status']);
        $optionRoleGroup = Utility::getOption($this->arrRoleGroup, $search['user_rid']);
        $messages = Utility::messages('messages');

        return view('Admin::user.list',[
            'data' => $dataSearch,
            'total' => $total,
            'paging' => $paging,
            'search' => $search,
            'optionStatus' => $optionStatus,
            'arrStatus' => $this->arrStatus,
            'optionRoleGroup' => $optionRoleGroup,
            'arrRoleGroup' => $this->arrRoleGroup,
            'messages' => $messages
        ]);
    }

    public function getItem($id = 0){
        $data = array();
        $session_user = User::userLogin();

        if ($id > 0){
            $data = User::getById($id);

            if ($session_user['user_rid'] != CGlobal::rid_admin && $session_user['user_rid'] != CGlobal::rid_teacher){
                if ($session_user['user_rid'] != $id){
                    Utility::messages('messages', 'Bạn không có quyền sửa người dùng!', 'error');
                    return Redirect::route('admin.user');
                }
            }
        }
        else{
            if ($session_user['user_rid'] != CGlobal::rid_admin && $session_user['user_rid'] != CGlobal::rid_teacher){
                Utility::messages('messages', 'Bạn không có quyền thêm người dung!', 'error');
            }
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : CGlobal::status_show);
        $optionRoleGroup = Utility::getOption($this->arrRoleGroup, isset($data['user_rid']) ? $data['user_rid']  : 0);

        if ($session_user['user_rid'] == CGlobal::rid_admin || $session_user['user_rid'] == CGlobal::rid_teacher){
            $theme = 'Admin::user.add';
        }
        else{
            $theme = 'Admin::user.addOther';
        }
        return view($theme, [
            'id' => $id,
            'data' => $data,
            'optionStatus' => $optionStatus,
            'optionRoleGroup' => $optionRoleGroup,
            'error' => $this->error
        ]);
    }

    public function postItem($id = 0){
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = array();
        $session_user = User::userLogin();

        $dataSave = array(
            'user_name' => array('value' => addslashes(Request::get('user_name')), 'require' => 1, 'messages' => 'Tên không được trống!'),
            'user_pass' => array('value' => addslashes(Request::get('user_pass')), 'require' => 1, 'messages' => 'Mật khẩu không được trống!'),
            're_user_pass'  => array('value' => addslashes(Request::get('re_user_pass')), 'require' => 1, 'messages' => 'Nhập lại mật khẩu không được trống!'),
            'user_full_name' => array('value' => addslashes(Request::get('user_full_name')), 'require' => 1, 'messages' => 'Tên hiển thị không được trống!'),
            'user_phone' => array('value' => addslashes(Request::get('user_phone')), 'require' => 0),
            'user_mail' => array('value' => addslashes(Request::get('user_mail')), 'require' => 0),
            'user_created' => array('value' => time(), 'require' => 0),
            'user_status' => array('value' => (int)Request::get('user_status', -1), 'require' => 0),
            'user_rid' => array('value' => (int)Request::get('user_rid'), 'require' => 0)
        );

        foreach ($dataSave as $key => $val){
            $data[$key] = $val['value'];
        }

        //Check
        $error = '';
        $id = ($id == 0) ? $id_hiden : $id;
        $name = $dataSave['user_name']['value'];
        $pass = $dataSave['user_pass']['value'];
        $repass = $dataSave['re_user_pass']['value'];

        if ($id > 0){
            //Edit
            unset($dataSave['user_created']);

            if($session_user['user_rid'] == CGlobal::rid_admin){
                if ($name != ''){
                    $check_valid_name = ValidForm::checkRegexName($name);
                    if (!$check_valid_name){
                        $error .= 'Tên không được có dấu! <br/>';
                    }
                }

                if ($pass != '' && ($pass === $repass)){
                    $check_valid_pass = ValidForm::checkRegexPass($pass, 6);
                    if ($check_valid_pass){
                        $hash_pass = User::encode_password($pass);
                        $dataSave['user_pass']['value'] = $hash_pass;
                        unset($dataSave['re_user_pass']);
                    }
                    else{
                        $error .= 'Mật khẩu không được ít hơn 6 ký tự và không được có dấu! <br/>';
                    }
                }

                if ($pass == '' && $repass == ''){
                    unset($dataSave['user_pass']);
                    unset($dataSave['re_user_pass']);
                }
                elseif ($pass != $repass){
                    $error .= 'Mật khẩu không khớp <br>';
                }

                $check = User::getUserByCond($id, $name);
                if (empty($check)){
                    Utility::messages('messages', 'Người dùng không tồn tại', 'error');
                    return Redirect::route('admin.user');
                }
            }
            else{
                if ($session_user['user_id'] == $id && $session_user['user_name'] == $name){
                    unset($dataSave['user_status']);
                    unset($dataSave['user_rid']);

                    if ($name != ''){
                        $check_valid_name = ValidForm::checkRegexName($name);
                        if (!$check_valid_name){
                            $error .= 'Tên không được trống! <br>';
                        }
                    }

                    if ($pass != '' && ($pass === $repass)){
                        $check_valid_pass = ValidForm::checkRegexPass($pass, 6);
                        if ($check_valid_pass){
                            $hash_pass = User::encode_password($pass);
                            $dataSave['user_pass']['value'] = $hash_pass;
                            unset($dataSave['re_user_pass']);
                        }
                        else{
                            $error .= 'Mật khẩu không được ít hơn 6 ký tự và không được có dấu <br/>';
                        }
                    }

                    if($pass == '' && $repass == ''){
                        unset($dataSave['re_user_pass']);
                        unset($dataSave['user_pass']);
                    }
                    elseif($pass != $repass){
                        $error .= 'Mật khẩu không khớp <br/>';
                    }
                }
                else{
                    Utility::messages('messages', 'Bạn không có quyền sửa người dùng này', 'error');
                    return Redirect::route('admin.user');
                }
            }
            unset($dataSave['user_name']);
        }
        else{
            //Add
            $check_valid_name = ValidForm::checkRegexName($name);
            if (!$check_valid_name){
                $error .= 'Tên không được có dấu <br/>';
            }

            if ($pass != '' && ($pass === $repass)){
                $check_valid_pass = ValidForm::checkRegexPass($pass, 6);
                if ($check_valid_pass){
                    $hash_pass = User::encode_password($pass);
                    $dataSave['user_pass']['value'] = $hash_pass;
                    unset($dataSave['re_user_pass']);
                }
                else{
                    $error .= 'Mật khẩu không được ít hơn 6 ký tự và không được có dấu <br>';
                }
            }
            elseif ($pass != $repass){
                $error .= 'Mật khẩu không khớp <br>';
            }

            //Check User Exists
            $check = User::getUserByName($name);
            if (!empty($check) != 0){
                $this->error = 'Tên đăng nhập đã tồn tại';
            }
        }
        //End Check User

        $this->error = ValidForm::validInputData($dataSave);
        $this->error .= $error;
        if ($this->error == ''){
            User::saveData($id, $dataSave);
            return Redirect::route('admin.user');
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : -1);
        $optionRoleGroup = Utility::getOption($this->arrRoleGroup, isset($data['user_rid']) ? $data['user_rid'] : 0);

        if ($session_user['user_rid'] == CGlobal::rid_admin || $session_user['user_rid'] == CGlobal::rid_teacher){
            $theme = 'Admin::user.add';
        }
        else{
            $theme = 'Admin::user.addOther';
        }

        return view($theme,[
            'id' => $id,
            'data' => $data,
            'optionStatus' => $optionStatus,
            'optionRoleGroup' => $optionRoleGroup,
            'error' => $this->error
        ]);
    }

    public function delete(){
        $session_user = User::userLogin();
        $listId = Request::get('checkItem', array());
        $token = Request::get('_token', '');

        if (Session::token() === $token){
            if (is_array($listId) && !empty($listId)){
                foreach ($listId as $id){
                    if ($session_user['user_id'] != $id){
                        User::deleteId($id);
                    }
                }
                Utility::messages('messages', 'Xóa thành công!', 'success');
            }
        }
        return Redirect::route('admin.user');
    }
}
