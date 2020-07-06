<?php

namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Memcache;
use App\Library\PHPDev\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PDOException;

class UserRoleGroup extends Model{
    protected $table = CDatabase::user_role_group;
    protected $primaryKey = 'group_role_id';
    public $timestamps = false;

    protected $fillable = [
        'group_role_id', 'group_role_title', 'group_role_list', 'group_role_order_no', 'group_role_status'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = UserRoleGroup::where('group_role_id', '>', 0);

            if (isset($dataSearch['group_role_title']) && $dataSearch['group_role_title'] != ''){
                $query->where('group_role_title', 'LIKE', '%'.$dataSearch['group_role_title'].'%');
            }
            if (isset($dataSearch['group_role_status']) && $dataSearch['group_role_status'] != -1){
                $query->where('group_role_status', $dataSearch['group_role_status']);
            }

            $total = $query->count(['group_role_id']);
            $query->orderBy('group_role_id', 'asc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }
            else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function getById($id = 0){
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ROLE_GROUP_ID.$id) : array();

        try {
            if (empty($result)){
                $result = UserRoleGroup::where('group_role_id', $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_ROLE_GROUP_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }catch (PDOException $e){
            throw new PDOException();
        }
        return $result;
    }

    public static function updateData($id = 0, $dataInput = array()){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data = UserRoleGroup::find($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->group_role_id) && $data->group_role_id > 0){
                    self::removeCacheId($data->group_role_id);
                }
            }
            DB::connection()->getPdo()->commit();
            return $id;

        }catch (PDOException $e){
            DB::connection()->getPdo()->rollback();
            throw new PDOException();
        }
    }

    public static function addData($dataInput = array()){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data = new UserRoleGroup();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->group_role_id && Memcache::CACHE_ON){
                   self::removeCacheId($data->group_role_id);
                }
                return $data->group_role_id;
            }
            DB::connection()->getPdo()->commit();
            return false;

        }catch (PDOException $e){
            DB::connection()->getPdo()->rollback();
            throw new PDOException();
        }
    }

    public static function saveData($id = 0, $data = array()){
        $data_post = array();

        if (!empty($data)){
            foreach ($data as $key => $val){
                $data_post[$key] = $val['value'];
            }
        }
        if ($id > 0){
            UserRoleGroup::updateData($id, $data_post);
            Utility::messages('messages', 'Cập nhật thành công!');
        }
        else{
            UserRoleGroup::addData($data_post);
            Utility::messages('messages', 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = UserRoleGroup::find($id);
            if ($data != null){
                $data->delete();
                if (isset($data->group_role_id) && $data->group_role_id > 0){
                    self::removeCacheId($data->group_role_id);
                }
                DB::connection()->getPdo()->commit();
            }
            return true;

        }catch (PDOException $e){
            DB::connection()->getPdo()->rollback();
            throw new PDOException();
        }
    }

    public static function removeCacheId($id = 0){
        if ($id > 0 ){
            Cache::forget(Memcache::CACHE_ROLE_GROUP_ID.$id);
        }
        Cache::forget(Memcache::CACHE_ROLE_GROUP_ALL);
    }

    public static function checkRoleExists($group_role_title, $id = 0){
        $result = array();

        if ($id == 0){
            $result = UserRoleGroup::where('group_role_title', $group_role_title)->first();
        }
        else{
            $result = UserRoleGroup::where('group_role_title', $group_role_title)->where('group_role_id', '!=', $id)->first();
        }
        return $result;
    }

    public static function getAllRoleGroup($dataSearch = array(), $limit = 0){
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ROLE_GROUP_ALL) : array();

        try {
            if (empty($result)){
                $query = UserRoleGroup::where('group_role_id', '>' , 0);
                $query->where('group_role_status', CGlobal::status_show);
                $query->orderBy('group_role_id', 'asc');

                $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
                if ($limit > 0){
                    $query->take($limit);
                }
                if (!empty($fields)){
                    $result = $query->get($fields);
                }
                else{
                    $result = $query->get();
                }

                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_ROLE_GROUP_ALL, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        }catch (PDOException $e){
            throw new PDOException();
        }
        return $result;
    }
}
