<?php
namespace App\Modules\Demo\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Pagging;
use App\Library\PHPDev\SEOMeta;
use App\Library\PHPDev\ThumbImg;
use App\Modules\Models\Demo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class DemoController extends BaseDemoController {
    public function listView($id = 0){
        $pageNo = (int)Request::get('page' , 1);
        $pageScroll = CGlobal::num_scroll_page;
        $limit = CGlobal::num_record_per_page_news;
        $offset = ($pageNo - 1)* $limit;
        $search = $data = array();
        $total  = 0;

        $data = Demo::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getPager($pageScroll, $pageNo, $total, $limit, $search) : '';
        $same = Demo::getItemSame($id, 10);

        return view('Demo::content.pageDemo',[
            'data' => $data,
            'paging' => $paging,
            'same' => $same
        ]);
    }

    public function detailDemo($name = '', $id = 0){
        $data  = array();
        if ($id > 0){
            $data = Demo::getById($id);
            if (!isset($data->demo_id)){
                return Redirect::route('page.404');
            }
        }
        if (isset($data->demo_id)){
            $same = Demo::getItemSame($id, 5);

            return view('Demo::content.pageDetailDemo', [
                'id'  => $id,
                'data' => $data,
                'same' => $same,
            ]);
        }
    }
}
