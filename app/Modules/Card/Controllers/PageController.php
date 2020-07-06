<?php
namespace App\Modules\Card\Controllers;

use App\Modules\Models\Card;

class PageController extends Controller{
    public function listView(){
        $card = Card::paginate(10)->onEachSide(1);
        return view('Card::page.trangchu', compact('card'));
    }
}
