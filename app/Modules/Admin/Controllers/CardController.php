<?php

namespace App\Modules\Admin\Controllers;

use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\Loader;
use App\Library\PHPDev\Utility;
use App\Modules\Models\Card;
use App\Modules\Models\Photo;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct()
    {
        Loader::loadJS('backend/js/admin.js', CGlobal::$postEnd);
        Loader::loadCSS('libs/jAlert/jquery.alerts.css', CGlobal::$postHead);
        Loader::loadJS('libs/jAlert/jquery.alerts.js', CGlobal::$postEnd);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $card = Card::paginate(10)->onEachSide(1);
        return view('Admin::card.index', compact('card'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);
        return view('Admin::card.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_title' => 'required' ,
            'card_content' => 'required',
            'meta_description' => 'required'
        ]);

        $card = new Card([
            'card_title' => $request->get('card_title'),
            'card_image' => $request->get('card_image'),
            'card_content' => $request->get('card_content'),
            'meta_description' => $request->get('meta_description'),
        ]);
        if ($request->hasFile('card_image')){
            $file  = $request->file('card_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' .$extension;
            $file->move('uploads/photo/img/', $filename);
            $card->card_image = $filename;
        }

        $card->save();
        return redirect('admin/cards')->with('success', 'Thêm mới thành công!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $card = Card::find($id);
        return view('Admin::card.show', compact('card'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Loader::loadJS('libs/ckeditor/ckeditor.js', CGlobal::$postHead);

        $card = Card::find($id);
        return view('Admin::card.edit', compact('card'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'card_title' => 'required',
            'card_content' => 'required',
            'meta_description' => 'required'
        ]);

        $card = Card::find($id);
        $card->card_title = $request->get('card_title');
        $card->card_image = $request->get('card_image');
        $card->card_content = $request->get('card_content');
        $card->meta_description = $request->get('meta_description');

        if ($request->hasFile('card_image')){
            $file  = $request->file('card_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' .$extension;
            $file->move('uploads/photo/img/', $filename);
            $card->card_image = $filename;
        }

        $card->save();
        return redirect('admin/cards')->with('success', 'Cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $listId = \Illuminate\Support\Facades\Request::get('checkItem', array());
        $token =  \Illuminate\Support\Facades\Request::get('_token', '');
        foreach ($listId as $id){
            Card::deleteId($id);
        }
        return redirect('admin/cards')->with('success', 'Xóa thành công!');
    }
}
