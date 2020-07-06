<?php

namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Memcache;
use App\Library\PHPDev\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use  PDOException;

class UserRole extends Model{
    protected $table = CDatabase::user_role;
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = [
        'role_id', 'role_title', 'role_order_no', 'role_status'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = UserRole::where('role_id', '>', 0);

            if (isset($dataSearch['role_title']) && $dataSearch['role_title'] != ''){
                $query->where('role_title', 'LIKE', '%'.$dataSearch['role_title'].'%');
            }
            if (isset($dataSearch['role_status']) && $dataSearch['role_status'] != -1){
                $query->where('role_status', $dataSearch['role_status']);
            }

            $total = $query->count(['role_id']);
            $query->orderBy('role_id', 'asc');

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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_ROLE_ID.$id) : array();

        try {
            if (empty($result)){
                $result = UserRole::where('role_id', $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_ROLE_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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

            $data = UserRole::find($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->role_id) && $data->role_id > 0){
                    self::removeCacheId($data->role_id);
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

            $data = new UserRole();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->role_id && Memcache::CACHE_ON){
                    self::removeCacheId($data->role_id);
                }
                return $data->role_id;
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
            UserRole::updateData($id, $data_post);
            Utility::messages('messages' , 'Cập nhật thành công!');
        }
        else{
            UserRole::addData($data_post);
            Utility::messages('messages', 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data  = UserRole::find($id);
            if ($data != null){
                $data->delete();
                if (isset($data->role_id) && $data->role_id > 0){
                    self::removeCacheId($data->role_id);
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
        if ($id > 0){
            Cache::forget(Memcache::CACHE_ROLE_ID.$id);
        }
    }

    public static function checkRoleExists($role_title, $id = 0){
        $result = array();

        if ($id == 0){
            $result = UserRole::where('role_title', $role_title)->first();
        }
        else{
            $result = UserRole::where('role_title', $role_title)->where('role_title', '!= ' ,$id)->first();
        }
        return $result;
    }

    public static function getAllRole($dataSearch = array(), $limit = 0){
        try {
            $query = UserRole::where('role_id', '>' ,0);
            $query->where('role_status', CGlobal::status_show);
            $query->orderBy('role_order_no', 'asc');

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
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
}
