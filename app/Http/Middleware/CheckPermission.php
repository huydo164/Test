<?php

namespace App\Http\Middleware;

use App\Modules\Models\User;
use App\Modules\Models\UserPermission;
use App\Modules\Models\UserRoleGroup;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\FuncLib;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CheckPermission{

    public function handle($request, Closure $next){

        if(!User::isLogin()) {
            return Redirect::route('login', array('url' =>  FuncLib::buildUrlEncode(URL::current())));
        }else{
            $user = User::userLogin();

            $role_group_id = isset($user['user_rid']) ? $user['user_rid'] : 0;
            if($role_group_id == CGlobal::rid_admin){
                return $next($request);
            }

            $UserRoleGroup = UserRoleGroup::getById($role_group_id);
            $roles_id = isset($UserRoleGroup->group_role_list) ? $UserRoleGroup->group_role_list : [];

            $arrrRoleId = explode(',', $roles_id);
            $permissions = UserPermission::getArrItemByRoleId($arrrRoleId, $role_group_id);

            $permissionRole = [];
            foreach($permissions as $key=>$permission){
                $permissionRole[] = $permission->permission_url;
            }

            $permissionRole = array_unique($permissionRole);
            $currentMethods = Route::getFacadeRoot()->current()->methods[0];
            $currentPath = Route::getFacadeRoot()->current()->uri();

            if(in_array($currentMethods."|".$currentPath, $permissionRole)){
                return $next($request);
            }else{
                return Redirect::route('admin.dashboard');
            }
        }
    }
}
