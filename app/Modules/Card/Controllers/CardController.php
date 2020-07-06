<?php
namespace App\Modules\Card\Controllers;

use App\Modules\Models\Card;

class CardController extends Controller {
    public function show($id)
    {
       $card = Card::find($id);
       return view('Card::content.pageCardDetail', compact('card'));
    }
}
