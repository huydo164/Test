<?php

namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\Memcache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use PDOException;

class UserPermission extends Model{
    protected $table = CDatabase::user_permission;
    protected $primaryKey = 'permission_id';
    public $timestamps = false;
    protected $fillable = array('permission_id', 'permission_group_name', 'permission_group_icon', 'permission_name', 'permission_as', 'permission_url', 'permission_icon', 'permission_menu_show', 'permission_role_id', 'permission_status');

    public static function deleteByRoleId($role_id = 0){
        try {
            if ($role_id > 0){
                UserPermission::where('permission_role_id', $role_id)->delete();
                self::removeCacheId($role_id);
            }
            return true;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function removeCacheId($role_id = 0 ){
        if ($role_id > 0){
            $user = User::userLogin();
            $role_group_id = isset($user['user_rid']) ? $user['user_rid'] : 0;
            Cache::forget(Memcache::CACHE_PERMISSION_BY_ROLE.$role_group_id);
        }
    }

    public static function getListPermission($user){
        try {
            $role_group_id = isset($user['user_rid']) ? $user['user_rid'] : 0;
            $UserRoleGroup = UserRoleGroup::getById($role_group_id);
            $roles_id = isset($UserRoleGroup->group_role_list) ? $UserRoleGroup->group_role_list : [];
            $arrrRoleId = explode(',' , $roles_id);
            $permissions = UserPermission::getArrItemByRoleId($arrrRoleId, $role_group_id);
            return $permissions;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function getArrItemByRoleId($arrrRoleId, $role_group_id){
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PERMISSION_BY_ROLE.$role_group_id) : array();

        try {
            if (empty($result)){
                if (!empty($arrrRoleId)){
                    $result = UserPermission::whereIn('permission_role_id', $arrrRoleId)->orderBy('permission_id', 'asc')->get();//Hàm whereIn dùng để kiểm tra giá trị của cột nằm trong một mảng;
                }
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_PERMISSION_BY_ROLE.$role_group_id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }catch (PDOException $e){
            throw new PDOException();
        }
        return $result;
    }

    public static function getArrItemByOneRoleId($role_id = 0){
        $result = [];

        try {
            if ($role_id > 0){
                $result = UserPermission::where('permission_role_id', $role_id)->get();
            }
        }catch (PDOException $e){
            throw new PDOException();
        }
        return $result;
    }

}
