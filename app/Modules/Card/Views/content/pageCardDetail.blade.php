@extends('Card::layout.html')
@section('title', $card->card_title)
@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="home-1">
                <div class="title">
                    {!! stripcslashes($card['card_title']) !!}
                </div>
                <div class="content">
                    {!! stripcslashes($card['card_content']) !!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            @include('Card::block.right')
        </div>
    </div>
@stop
