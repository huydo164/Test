<?php
use App\Library\PHPDev\FuncLib;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
?>
@extends('Demo::layout.html')
@section('content')
    <div class="bd-bottom">
        <div class="line bgebebeb">
            <div class="container">
                <ul class="breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{FuncLib::getBaseUrl()}}">Trang chủ</a>
                    </li>
                    <li class="active">Tin Tức</li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="line">
                <div class="new">
                    Tin tức
                </div>
                <div class="row">
                    <div class="line mgt20 listPostNews">
                        @if($data->count() > 1)
                            <div class="col-lg-9">
                                @foreach($data as $item)
                                    <div class=" col-lg-12 item-post-cols">
                                        <div class="item">
                                            <a title="{{$item->demo_title}}" href="{{FuncLib::buildLinkDetailDemo($item->demo_id, $item->demo_title)}}">
                                                <div class="col-lg-3 thumbI">
                                                    @if($item->demo_image != '')
                                                        <img alt="{{$item->demo_title}}" src="{{ThumbImg::thumbBaseNormal(CGlobal::FOLDER_DEMO, $item->demo_id, $item->demo_image, 200, 200, '', true, true)}}">
                                                    @endif
                                                </div>
                                                <div class="col-lg-9">
                                                    <h3 class="titlex">{{stripcslashes($item->demo_title)}}</h3>
                                                    <div class="line">
                                                        <i class="fa fa-calendar"></i>
                                                        <span class="item-date0">{{date('d-m-Y H:i', $item->demo_created)}}</span>
                                                    </div>
                                                    <div class="des">
                                                        {{ stripcslashes($item->meta_description) }}
                                                    </div>
                                                    <div class="show-more">
                                                        <a href="{{FuncLib::buildLinkDetailDemo($item->demo_id, $item->demo_title)}}" title="{{ $item->demo_title }}">Xem thêm</a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-3">
                                @include('Demo::block.right')
                            </div>
                            <div class="col-lg-12">
                                <div class="show-box-paging">{!! $paging !!}</div>
                            </div>
                        @else
                            <div class="col-lg-12">
                                @foreach($data as $k=>$item)
                                    <div class="content-view">
                                        {!!stripslashes($item['meta_description'])!!}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
