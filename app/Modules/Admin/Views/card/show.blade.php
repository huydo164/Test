@extends('Admin::layout.html')
@section('content')
<div class="container">
    <div class="content">
        <div class="title">
            {!! stripcslashes($card['card_title']) !!}
        </div>
        <div class="card-content">
            {!! stripcslashes($card['card_content']) !!}
        </div>
    </div>
</div>
@stop
