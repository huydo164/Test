<?php
use App\Library\PHPDev\FuncLib;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
use App\Library\PHPDev\Loader;
?>
@extends('Demo::layout.html')
@section('content')
    <div class="bd-bottom viewPost">
        <div class="line bgebebeb">
            <div class="container">
                <ul class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{FuncLib::getBaseUrl()}}">Trang chủ</a>
                    </li>
                    @if(isset($data->demo_id))
                        <li class="active">
                            {{ stripcslashes($data->demo_title) }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-12">
                    <div class="demo-content">
                        <h4 class="title-view">{{stripcslashes($data->demo_title)}}</h4>
                        <div class="line">
                            <div class="line content-view">
                                {!! stripcslashes($data->demo_content) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-10">
                            <div class="title-same">Bài viết khác:</div>
                            <div class="line mgt15 mgbt10 listPostNews">
                                <div class="row">
                                    @foreach($same as $item)
                                        <div class="col-lg-12 col-md-12 col-sm-12 item-post-news">
                                            <a href="" >{{ stripcslashes($item->demo_title) }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    @include('Demo::block.right')
                </div>
            </div>
        </div>
    </div>
@stop
