<?php
namespace App\Modules\Models;

use App\Library\PHPDev\CDatabase;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Memcache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Card extends Model{
    protected $table = CDatabase::card;
    protected $primaryKey = 'card_id';
    public $timestamps = true;

    protected $fillable = [
        'card_id', 'card_title' , 'card_image', 'card_content', 'created_at', 'update_at', 'meta_description'
    ];
    public static function deleteId($id = 0){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $data = Card::find($id);
            if ($data != null){

                $data->delete();
                if (isset($data->card_id) && $data->card_id > 0){
                    self::removeCacheId($data->card_id);
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
            Cache::forget(Memcache::CACHE_CARD_ID.$id);
        }
    }
}
