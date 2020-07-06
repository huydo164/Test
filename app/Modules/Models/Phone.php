<?php
namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Memcache;
use App\Library\PHPDev\Utility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDOException;

class Phone extends Model{
    protected $table = CDatabase::phone;
    protected $primaryKey = 'phone_id';
    public $timestamps = false;

    protected $fillable = [
        'phone_id', 'phone_title', 'phone_content', 'meta_description', 'phone_created', 'phone_image', 'phone_image_other', 'phone_status', 'phone_focus'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = Phone::where('phone_id', '>', 0);
            if (isset($dataSearch['phone_title']) && $dataSearch['phone_title'] != ''){
                $query->where('phone_title' , 'LIKE', '%'.$dataSearch['phone_title'] . '%');
            }
            if (isset($dataSearch['phone_status']) && $dataSearch['phone_status'] != -1){
                $query->where('phone_status', $dataSearch['phone_status']);
            }
            $total = $query->count(['phone_id']);
            $query->orderBy('phone_id', 'desc');

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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PHONE_ID.$id) : array();
        try {
            if (empty($result)){
                $result = Phone::where('phone_id' , $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_PHONE_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $data = Phone::getById($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->phone_id) && $data->phone_id > 0){
                    self::removeCacheId($data->phone_id);
                }
            }
            DB::connection()->getPdo()->commit();
            return true;
        }catch (PDOException $e){
            DB::connection()->getPdo()->rollback();
            throw new PDOException();
        }
    }

    public static function addData($dataInput = array()){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = new Phone();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->phone_id && Memcache::CACHE_ON){
                    Phone::removeCacheId($data->phone_id);
                }
                return $data->phone_id;
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
            Phone::updateData($id, $data_post);
            Utility::messages('messages' ,'Cập nhật thành công!');
        }
        else{
            Phone::addData($data_post);
            Utility::messages('messages', 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id=0){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = Phone::find($id);
            if($data != null){

                //Remove Img
                $phone_image_other = ($data->phone_image_other != '') ? unserialize($data->phone_image_other) : array();
                if(is_array($phone_image_other) && !empty($phone_image_other)){
                    $path = Config::get('config.DIR_ROOT').'uploads/'.CGlobal::FOLDER_PHONE.'/'.$id;
                    foreach($phone_image_other as $v){
                        if(is_file($path.'/'.$v)){
                            @unlink($path.'/'.$v);
                        }
                    }
                    if(is_dir($path)) {
                        @rmdir($path);
                    }
                }
                //End Remove Img
                $data->delete();
                if(isset($data->phone_id) && $data->phone_id > 0){
                    self::removeCacheId($data->phone_id);
                }
                DB::connection()->getPdo()->commit();
            }
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollback();
            throw new PDOException();
        }
    }

    public static function removeCacheId($id=0){
        if($id>0){
            Cache::forget(Memcache::CACHE_PHONE_ID.$id);
        }
    }
}
