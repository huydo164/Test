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

class Demo extends Model{
    protected $table = CDatabase::demo;
    protected $primaryKey = 'demo_id';
    public $timestamps = false;

    protected $fillable = [
        'demo_id', 'demo_title', 'demo_content', 'meta_description', 'demo_image' , 'demo_image_other', 'demo_status' , 'demo_focus' , 'demo_created'
    ];

    public static function searchByCondition($dataSearch = array() ,  $limit = 0, $offset = 0, &$total){
        try {
            $query = Demo::where('demo_id', '>', 0);

            if (isset($dataSearch['demo_title']) && $dataSearch['demo_title'] != ''){
                $query->where('demo_title' , 'LIKE', '%'.$dataSearch['demo_title'] . '%');
            }
            if (isset($dataSearch['demo_status']) && $dataSearch['demo_status'] != -1){
                $query->where('demo_status',  $dataSearch['demo_status']);
            }

            $total = $query->count(['demo_id']);
            $query->orderBy('demo_id', 'asc');
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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_DEMO_ID.$id) : array();
        try {
            if (empty($result)){
                $result = Demo::where('demo_id' , $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_DEMO_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $data = Demo::getById($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->demo_id) && $data->demo_id > 0){
                    self::removeCacheId($data->demo_id);
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
            $data = new Demo();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->demo_id && Memcache::CACHE_ON){
                    Demo::removeCacheId($data->demo_id);
                }
                return $data->demo_id;
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
            Demo::updateData($id, $data_post);
            Utility::messages('messages' , 'Cập nhật thành công!' );
        }else{
            Demo::addData($data_post);
            Utility::messages('messages' , 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = Demo::find($id);
            if ($data != null){

                //Xóa ảnh trong Folder
                $demo_image_other = ($data->demo_image_other != '') ? unserialize($data->demo_image_other) : array();
                if (is_array($demo_image_other) && !empty($demo_image_other)){
                    $path = Config::get('config.DIR_ROOT').'uploads/' .CGlobal::FOLDER_DEMO.'/'.$id;
                    foreach ($demo_image_other as $v){
                        if (is_file($path .'/'. $v)){
                            @unlink($path .'/'. $v);
                        }
                    }
                    if (is_dir($path)){
                        @rmdir($path);
                    }
                }
                $data->delete();
                if (isset($data->demo_id) && $data->demo_id > 0){
                    self::removeCacheId($data->demo_id);
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
            Cache::forget(Memcache::CACHE_DEMO_ID.$id);
        }
    }
    public static function getItemSame($id=0 ,  $limit = 10){
        try {
            $query = Demo::where('demo_id', '>', 0);
            $query->where('demo_id', '<>', $id);
            $query->orderBy('demo_id', 'asc');
            $results = $query->take($limit)->get();
            return $results;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }
}
