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

class Photo extends Model{
    protected $table = CDatabase::photo;
    protected $primaryKey = 'photo_id';
    public $timestamps = false;

    protected $fillable = [
        'photo_id', 'photo_title', 'photo_content', 'photo_image', 'photo_image_other', 'photo_status', 'photo_focus', 'meta_description', 'photo_created'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = Photo::where('photo_id', '>', 0);

            if (isset($dataSearch['photo_title']) && $dataSearch['photo_title'] != ''){
                $query->where('photo_title', 'LIKE', '%'.$dataSearch['photo_title'] .'%');
            }
            if (isset($dataSearch['photo_status']) && $dataSearch['photo_status'] != -1){
                $query->where('photo_status', $dataSearch['photo_status']);
            }
            $total = $query->count(['photo_id']);
            $query->orderBy('photo_id');

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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PHOTO_ID.$id) : array();

        try {
            if (empty($result)){
                $result = Photo::where('photo_id', $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_PHOTO_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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

            $data = Photo::getById($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->photo_id) && $data->photo_id > 0){
                    self::removeCacheId($data->photo_id);
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

            $data = new Photo();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->photo_id && $data->photo_id > 0){
                    Photo::removeCacheId($data->photo_id);
                }
                return $data->photo_id;
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
            Photo::updateData($id, $data_post);
            Utility::messages('messages' , 'Cập nhật thành công!');
        }
        else{
            Photo::addData($data_post);
            Utility::messages('messages' , 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data = Photo::find($id);
            if ($data != null){
                //Xóa ảnh trong thư mục
                $photo_image_other = ($data->photo_image_other != '') ? unserialize($data->photo_image_other) : array();
                if (is_array($photo_image_other) && !empty($photo_image_other)){
                    $path = Config::get('Config.DIR_ROOT').'uploads'.CGlobal::FOLDER_PHOTO.'/'.$id;
                    foreach ($photo_image_other as $v){
                        if (is_file($path . '/' .$v)){
                            @unlink($path .'/'.$v);
                        }
                    }
                    if (is_dir($path)){
                        @rmdir($path);
                    }
                }
                $data->delete();
                if (isset($data->photo_id) && $data->photo_id > 0){
                    self::removeCacheId($data->photo_id);
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
            Cache::forget(Memcache::CACHE_PHOTO_ID.$id);
        }
    }
}
