<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\ValidForm;
use App\Modules\Models\UserPermission;
use App\Modules\Models\UserRole;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class UserRoleController extends BaseAdminController {
    private $arrStatus = array(-1 => 'Chọn', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');
    private $error = '';

    public function __construct()
    {
        parent::__construct();
        Loader::loadJS('backend/js/admin.js', CGlobal::$postEnd);
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

        $search['role_title'] = addslashes(Request::get('role_title', ''));
        $search['role_status'] = (int)Request::get('role_status', -1);
        $search['submit'] = (int)Request::get('submit', 0);
        $search['field_get'] = '';

        $dataSearch = UserRole::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search) : '';

        $optionStatus = Utility::getOption($this->arrStatus, $search['role_status']);
        $messages = Utility::messages('messages');

        return view('Admin::userRole.list',[
            'data' => $dataSearch,
            'search' => $search,
            'total' => $total,
            'paging' => $paging,
            'arrStatus' => $this->arrStatus,
            'optionStatus'  => $optionStatus,
            'messages' => $messages
        ]);
    }

    public function getItem($id = 0){
        $data = array();

        if ($id > 0){
            $data = UserRole::getById($id);
        }
        $optionStatus = Utility::getOption($this->arrStatus, isset($data->role_status) ? $data->role_status : CGlobal::status_show);

        return view('Admin::userRole.add',[
            'id' => $id,
            'data' => $data,
            'optionStatus' => $optionStatus,
            'error' => $this->error,
            'arrStatus' => $this->arrStatus
        ]);
    }

    public function postItem($id = 0){
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = array();

        $dataSave = array(
            'role_title' => array('value' => addslashes(Request::get('role_title')), 'require' => 1 , 'messages' => 'Tên không được trống!'),
            'role_status' => array('value' => (int)Request::get('role_status' , -1), 'require' => 0),
            'role_order_no' => array('value' => (int)Request::get('role_order_no' , 0), 'require' => 0),
        );

        $this->error = ValidForm::validInputData($dataSave);
        $checkExists = UserRole::checkRoleExists($dataSave['role_title']['value'], $id);
        if (!empty($checkExists) > 0){
            $this->error = 'Quyền'. $dataSave['role_title']['value'] . 'đâ tồn tại <br/>';
        }

        if ($this->error == ''){
            $id = ($id == 0) ? $id_hiden : $id;
            UserRole::saveData($id, $dataSave);
            return Redirect::route('admin.role');
        }
        else{
            foreach ($dataSave as $key => $val){
                $data[$key] = $val['value'];
            }
        }

        $optionStatus = Utility::getOption($this->arrStatus, isset($data['role_title']) ? $data['role_title'] : -1);

        return view('Admin::userRole.add',[
            'id' => $id,
            'data' => $data,
            'optionStatus' => $optionStatus,
            'arrStatus' => $this->arrStatus,
            'error' => $this->error,
        ]);
    }

    public function delete(){
        $listId = Request::get('checkItem', array());
        $token = Request::get('_token', '');

        if (Session::token() === $token){
            if (!empty($listId) && is_array($listId)){
                foreach ($listId as $id){
                    UserRole::deleteId($id);
                }
                Utility::messages('messages', 'Xóa thành công!', 'success');
            }
        }
        return Redirect::route('admin.role');
    }

    public function permission($id = 0){
        $routeCollection  = Route::getRoutes();//Lấy tất cả các route đã tạo trong routes
        $records = array();

        foreach ($routeCollection as $key => $value){
            if (isset($value->action['group']) && isset($value->action['group_name'])){
                $records[$value->action['group']]['name'] = $value->action['group_name'];//lấy giá trị group_name trong routes gán giá trị vào records group_name
                $records[$value->action['group']]['icon'] = $value->action['display_icon'];//lấy giá trị display_icon trong routes gán giá trị vào records display_icon
                $records[$value->action['group']]['sub'][] = $value;//giá trị value thừa hưởng routes từ $routecollection gán vào $records sub
            }
        }

        $permissions = UserPermission::getArrItemByOneRoleId($id);//Hàm getArrItemByOneRoleId sử dụng khi một acc được phân quyền vào những ô phân quyền thì nó chỉ đc truy cập vào những quyền nó đc phân
        $array = [];

        foreach ($permissions as $permission){
            $array[] = $permission->permission_url;// lấy ra địa chỉ của routes
        }

        return view('Admin::userRole.permission',[
            'id' => $id,
            'records' => $records,
            'permission' => $array
        ]);
    }

    public function permissionSave($id = 0){
        $role_id = Request::get('id', 0);
        $permission_id = Request::get('permission_id', array());//lấy ra id của routes đã tích phân quyền và trả về dạng mảng
        $permission_name = Request::get('name', array());//lấy ra các name đã đặt trong routes và trả về dạng mảng
        $permission_icon = Request::get('show_icon', array());//lấy ra các icon đã đặt trong routes và trả về dạng mảng
        $permission_as = Request::get('action_as', array());//lấy ra các as đã đặt trong routes và trả về dạng mảng
        $permission_menu_show = Request::get('menu', array());
        $permission_group_name = Request::get('group_name', array());
        $permission_group_icon = Request::get('group_icon', array());

        if ($role_id > 0 && $role_id == $id){
            UserPermission::deleteByRoleId($role_id);
            if (!empty($permission_id)){
                foreach ($permission_id as $key => $permission){
                    if (is_array($permission) && !empty($permission)){
                        foreach ($permission as $k => $v){
                            $model = new UserPermission();
                            $model->permission_name = isset($permission_name[$key][$v]) ? $permission_name[$key][$v] : '';
                            $model->permission_menu_show = isset($permission_menu_show[$key][$v]) ? $permission_menu_show[$key][$v] : 0;
                            $model->permission_as = isset($permission_as[$key][$v]) ? $permission_as[$key][$v] : '';
                            $model->permission_icon = isset($permission_icon[$key][$v]) ? $permission_icon[$key][$v] : '';
                            $model->permission_url = $v;
                            $model->permission_group_icon = isset($permission_group_icon[$key][$v]) ? $permission_group_icon[$key][$v] : '';
                            $model->permission_group_name = isset($permission_group_name[$key][$v])  ? $permission_group_name[$key][$v] : '';
                            $model->permission_role_id = $role_id;
                            $model->permission_status = CGlobal::status_show;
                            $model->save();
                        }
                    }
                }
            }
        }
        return Redirect::route('admin.role');
    }
}
