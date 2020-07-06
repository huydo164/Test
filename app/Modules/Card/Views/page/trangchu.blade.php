<?php
use App\Library\PHPDev\FuncLib;
?>
@extends('Card::layout.html')
@section('title', 'Mua thẻ điện thoại, thẻ game')
@section('content')
    <div class="container">
        <div class="home-1">
            <h4>Tin tức</h4>
            <div class="row">
               <div class="col-lg-9">
                   @foreach($card as $item)
                       <div class="list">
                           <a href="{{ route('card.show', $item->card_id) }}" title="{{ $item->card_title }}">
                               <div class="img-cont col-lg-3">
                                   <img id="img" class="thumb" src="{{ URL::asset('uploads/photo/img/' . $item->card_image) }}">
                               </div>
                               <div class="title-cont col-lg-9">
                                   <div class="title-card">{{ $item->card_title }}</div>
                                   <div class="date">
                                       <i class="fa fa-calendar"></i>
                                       <span class="nghieng">{{ $item->created_at }}</span>
                                   </div>
                                   <div class="content-card">{{ $item->meta_description }}</div>
                                   <div class="show-more">
                                       <a href="">Xem thêm</a>
                                   </div>
                               </div>
                           </a>
                       </div>
                   @endforeach
               </div>
                <div class="col-lg-3">
                    @include('Card::block.right')
                </div>
            </div>
            <div class="page">
                {!! $card->links() !!}
            </div>
        </div>
    </div>
@stop
