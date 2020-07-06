<?php
namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\CGlobal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Library\PHPDev\Memcache;
use App\Library\PHPDev\Utility;
use PDOException;

class User extends Model {

    protected $table = CDatabase::user;
    protected $primaryKey = 'user_id';
    public  $timestamps = false;

    protected $fillable = array(
        'user_id', 'user_name', 'user_pass', 'user_full_name',
        'user_phone', 'user_mail', 'user_last_login',
        'user_last_ip', 'user_created', 'user_status', 'user_rid', 'user_image','session_id');
    //ADMIN
    public static function searchByCondition($dataSearch=array(), $limit=0, $offset=0, &$total){
        try{

            $query = User::where('user_id','>',0);

            if (isset($dataSearch['user_rid']) && $dataSearch['user_rid'] != -1) {
                $query->where('user_rid', $dataSearch['user_rid']);
            }
            if (isset($dataSearch['user_full_name']) && $dataSearch['user_full_name'] != '') {
                $query->where('user_full_name','LIKE', '%' . $dataSearch['user_full_name'] . '%');
            }
            if (isset($dataSearch['user_name']) && $dataSearch['user_name'] != '') {
                $query->where('user_name','LIKE', '%' . $dataSearch['user_name'] . '%');
            }
            if (isset($dataSearch['user_phone']) && $dataSearch['user_phone'] != '') {
                $query->where('user_phone','LIKE', '%' . $dataSearch['user_phone'] . '%');
            }
            if (isset($dataSearch['user_mail']) && $dataSearch['user_mail'] != '') {
                $query->where('user_mail','LIKE', '%' . $dataSearch['user_mail'] . '%');
            }
            if (isset($dataSearch['user_status']) && $dataSearch['user_status'] != -1) {
                $query->where('user_status', $dataSearch['user_status']);
            }
            if (isset($dataSearch['user_id']) && $dataSearch['user_id'] != -1) {
                $query->where('user_id', $dataSearch['user_id']);
            }

            $total = $query->count();
            $query->orderBy('user_id', 'asc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        }catch (PDOException $e){
            throw new PDOException();
        }
    }
    public static function getById($id=0){
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_USER_ID.$id) : array();
        try {
            if(empty($result)){
                $result = User::where('user_id', $id)->first();
                if($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_USER_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
                    //Cache::put(): lưu các item vào trong cache
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
    }
    public static function updateData($id=0, $dataInput=array()){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = User::find($id);
            if($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if(isset($data->user_id) && $data->user_id > 0){
                    self::removeCacheId($data->user_id);
                }
            }
            DB::connection()->getPdo()->commit();
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function addData($dataInput=array()){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = new User();
            if (is_array($dataInput) && count($dataInput) > 0) {
                foreach ($dataInput as $k => $v) {
                    $data->$k = $v;
                }
            }
            if ($data->save()) {
                DB::connection()->getPdo()->commit();
                if($data->user_id && Memcache::CACHE_ON){
                    User::removeCacheId($data->user_id);
                }
                return $data->user_id;
            }
            DB::connection()->getPdo()->commit();
            return false;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function saveData($id=0, $data=array()){
        $data_post = array();
        if(!empty($data)){
            foreach($data as $key=>$val){
                $data_post[$key] = $val['value'];
            }
        }
        if($id > 0){
            User::updateData($id, $data_post);
            Utility::messages('messages', 'Cập nhật thành công!');
        }else{
            User::addData($data_post);
            Utility::messages('messages', 'Thêm mới thành công!');
        }

    }
    public static function deleteId($id=0){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = User::find($id);
            if($data != null){
                $data->delete();
                if(isset($data->user_id) && $data->user_id > 0){
                    self::removeCacheId($data->user_id);
                }
                DB::connection()->getPdo()->commit();
            }
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function removeCacheId($id=0){
        if($id > 0){
            Cache::forget(Memcache::CACHE_USER_ID.$id);
        }
    }
    //Check User...
    public static function getUserByCond($id, $name){
        $result = User::where('user_id', $id)->where('user_name', $name)->first();
        return $result;
    }
    public static function getUserByName($name){
        $result = User::where('user_name', $name)->first();
        return $result;
    }
    public static function encode_password($password){
        return md5(md5($password));
    }
    public static function updateLogin($user = array(), $session_id){
        if($user){
            $user->user_last_login = time();
            $user->user_last_ip = request()->ip();
            $user->session_id = $session_id;
            $user->save();
        }
    }
    public static function isLogin(){
        $result = 0;
        if(Session::has('user')){
            $result = 1;
        }
        return $result;
    }
    public static function userLogin(){
        $user = array();
        if(Session::has('user')){
            $user = Session::get('user');
        }
        return $user;
    }
}
