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

class Statics extends Model{
    protected $table = CDatabase::statics;
    protected $primaryKey = 'statics_id';
    public $timestamps = false;

    protected $fillable = [
        'statics_id', 'statics_title', 'statics_content', 'statics_image', 'statics_image_other', 'statics_status', 'statics_focus', 'statics_created', 'meta_description'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = Statics::where('statics_id', '>',0);

            if (isset($dataSearch['statics_title']) && $dataSearch['statics_title'] != ''){
                $query->where('statics_title', 'LIKE', '%'.$dataSearch['statics_title']. '%');
            }
            if (isset($dataSearch['statics_status']) && $dataSearch['statics_status'] != -1){
                $query->where('statics_status', $dataSearch['statics_status']);
            }

            $total = $query->count(['statics_id']);
            $query->orderBy('statics_id', 'asc');

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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_STATICS_ID.$id) : array();

        try {
            if (empty($result)){
                $result = Statics::where('statics_id', $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_STATICS_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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

            $data = Statics::getById($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->statics_id) && $data->statics_id > 0){
                    self::removeCacheId($data->statics_id);
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

            $data = new Statics();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->statics_id && Memcache::CACHE_ON){
                    Statics::removeCacheId($data->statics_id);
                }
                return $data->statics_id;
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
            foreach ($data as $key => $val) {
                $data_post[$key] = $val['value'];
            }
        }
        if ($id > 0){
            Statics::updateData($id, $data_post);
            Utility::messages('messages', 'Cập nhật thành công!');
        }
        else{
            Statics::addData($data_post);
            Utility::messages('messages' , 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data = Statics::find($id);
            if ($data != null){
                $statics_image_other = ($data->statics_image_other != '') ? unserialize($data->statics_image_other) : array();
                if (is_array($statics_image_other) && !empty($statics_image_other)){
                    $path = Config::get('Config.DIR_ROOT').'uploads/'.CGlobal::FOLDER_STATICS.'/'.$id;
                    foreach ($statics_image_other as $v){
                        if (is_file($path .'/'.$v)){
                            @unlink($path.'/'.$v);
                        }
                    }
                    if (is_dir($path)){
                        @rmdir($path);
                    }
                }
                $data->delete();
                if (isset($data->statics_id) && $data->statics_id > 0){
                    self::removeCacheId($data->statics_id);
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
            Cache::forget(Memcache::CACHE_STATICS_ID.$id);
        }
    }
}
