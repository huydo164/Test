<?php
use App\Library\PHPDev\FuncLib;
use App\Library\PHPDev\CGlobal;
?>

@extends('Admin::layout.html')
@section('header')
    @include('Admin::block.header')
@stop
@section('left')
    @include('Admin::block.left')
@stop
@section('content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
                    </li>
                    <li class="active">Quản lý Thẻ</li>
                </ul>
            </div>
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-info">
                            <form id="frmSearch" method="GET" action="" class="frmSearch" name="frmSearch">
                                <div class="panel-footer text-right">
                                    <a class="btn btn-primary btn-sm" href="{{FuncLib::getBaseUrl()}}admin/cards/create"><i class="ace-icon fa fa-plus-circle"></i>Thêm mới</a>
                                    <a href="javascript:void(0)" title="Xóa item" id="deleteMoreItem" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12">
                            @if(session()->get('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        </div>
                        @if(sizeof($card) > 0)
                            <form id="formListItem" method="POST" action="{{FuncLib::getBaseUrl()}}admin/cards/delete" class="formListItem" name="txtForm">
                                @method('DELETE')
                                <table class="table table-bordered table-hover">
                                    <thead class="thin-border-bottom">
                                    <tr>
                                        <th width="2%">STT</th>
                                        <th width="1%">
                                            <label class="pos-rel">
                                                <input id="checkAll" class="ace" type="checkbox">
                                                <span class="lbl"></span>
                                            </label>
                                        </th>
                                        <th width="20%">Tiêu đề</th>
                                        <th width="5%">Ngày tạo</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($card as $k=>$item)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>
                                                <label class="pos-rel">
                                                    <input class="ace checkItem" name="checkItem[]" value="{{$item['card_id']}}" type="checkbox">
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td><a target="_blank" href="{{ route('cards.show', $item->card_id) }}" title="{{$item->card_title}}">{{$item['card_title']}}</a></td>
                                            <td>{{ $item['created_at']}}</td>
                                            <td>
                                                <a href="{{ route('cards.edit' , $item->card_id) }}" title="Cập nhật">
                                                    <i class="fa fa-edit fa-admin"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="show-bottom-info">
                                    <div class="list-item-page">
                                        <div class="showListPage">{!! $card->links() !!}</div>
                                    </div>
                                </div>
                                {!! csrf_field() !!}
                            </form>
                        @else
                            <div class="alert">
                                Không có dữ liệu
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
