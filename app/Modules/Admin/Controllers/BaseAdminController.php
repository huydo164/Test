<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Modules\Models\User;
use App\Modules\Models\UserPermission;
use Illuminate\Support\Facades\View;

class BaseAdminController extends Controller{
    protected $user = array();

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $this->user = User::userLogin();
            View::share('menu', $this->menu($this->user));
            View::share('user', $this->user);
            return $next($request);
        });
    }

    public function menu($user){
        $permissions = UserPermission::getListPermission($user);
        $menu = [];
        $action = [];
        if($permissions->count() > 0){
            foreach($permissions as $item){
                if($item->permission_menu_show == CGlobal::status_show){
                    $permission_url = explode('|', $item->permission_url);
                    if(!in_array($item->permission_as, $action)){
                        $tmp = [
                            'permission_name' => $item->permission_name,
                            'permission_url' => isset($permission_url[1]) ? $permission_url[1] : '',
                            'permission_as' => $item->permission_as,
                            'permission_icon' => $item->permission_icon,
                        ];
                        $menu[$item->permission_group_name]['sub'][] = $tmp;
                        $menu[$item->permission_group_name]['icon'] = $item->permission_group_icon;
                        $action[$item->permission_as] = $item->permission_as;
                    }
                }
            }
        }
        return $menu;
    }
}
