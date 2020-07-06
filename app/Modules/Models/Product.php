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

class Product extends Model{
    protected $table = CDatabase::product;
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
      'product_id', 'product_title', 'product_content', 'product_status', 'product_focus', 'product_image', 'product_image_other', 'product_created', 'meta_description'
    ];

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total){
        try {
            $query = Product::where('product_id', '>',0);

            if (isset($dataSearch['product_title']) && $dataSearch['product_title'] != ''){
                $query->where('product_title', 'LIKE', '%'.$dataSearch['product_title'].'%');
            }
            if (isset($dataSearch['product_status']) && $dataSearch['product_status'] != -1){
                $query->where('product_status', $dataSearch['product_status']);
            }

            $total = $query->count(['product_id']);
            $query->orderBy('product_id', 'asc');
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', $dataSearch['field_get']) : array();

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
        $result = (Memcache::CACHE_ON) ? Cache::get(Memcache::CACHE_PRODUCT_ID.$id) : array();

        try {
            if (empty($result)){
                $result = Product::where('product_id', $id)->first();
                if ($result && Memcache::CACHE_ON){
                    Cache::put(Memcache::CACHE_PRODUCT_ID.$id, $result, Memcache::CACHE_TIME_TO_LIVE_ONE_MONTH);
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

            $data =  Product::getById($id);
            if ($id > 0 && !empty($dataInput)){
                $data->update($dataInput);
                if (isset($data->product_id) && $data->product_id > 0){
                    self::removeCacheId($data->product_id);
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

            $data = new Product();
            if (is_array($dataInput) && count($dataInput) > 0){
                foreach ($dataInput as $k => $v){
                    $data->$k = $v;
                }
            }
            if ($data->save()){
                DB::connection()->getPdo()->commit();
                if ($data->product_id && Memcache::CACHE_ON){
                    Product::removeCacheId($data->product_id);
                }
                return $data->product_id;
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
            Product::updateData($id, $data_post);
            Utility::messages('messages', 'Cập nhật thành công!');
        }
        else{
            Product::addData($data_post);
            Utility::messages('messages', 'Thêm mới thành công!');
        }
    }

    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();

            $data = Product::find($id);
            if ($data != null){
                //Xóa ảnh trong thư mục
                $product_image_other = ($data->product_image_other != '') ? unserialize($data->product_image_other) : array();
                if (is_array($product_image_other) && !empty($product_image_other)){
                    $path = Config::get('Config.DIR_ROOT').'uploads'.CGlobal::FOLDER_PRODUCT.'/'.$id;
                    foreach ($product_image_other as $v){
                        if (is_file($path .'/'.$v)){
                            @unlink($path .'/'.$v);
                        }
                    }
                    if (is_dir($path)){
                        @rmdir($path);
                    }
                }
                $data->delete();
                if (isset($data->product_id) && $data->product_id > 0){
                    self::removeCacheId($data->product_id);
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
            Cache::forget(Memcache::CACHE_PRODUCT_ID.$id);
        }
    }
}
