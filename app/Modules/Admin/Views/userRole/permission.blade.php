<?php
use App\Library\PHPDev\FuncLib;
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
                    <li class="active">@if($id==0)Thêm mới @else Sửa @endif quyền</li>
                </ul>
            </div>
            <div class="page-content">
                <div class="col-xs-12">
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label"><b>Danh sách quyền truy cập</b> <span class="red pointer btnClickAllAction"><i>Click chọn tất cả</i></span></label>
                            <div class="controls">
                                <form class="form-horizontal paddingTop30" name="txtForm" action="{{FuncLib::getBaseUrl()}}admin/role/permission/{{$id}}" method="post">
                                    @if(isset($records) && isset($permission))
                                        @foreach($records as $key=>$record)
                                            @if(isset($record['name']) && isset($record['sub']))
                                                <h4 class="theader">{{$record['name']}}</h4>
                                                <div class="row">
                                                    @foreach($record['sub'] as $v)
                                                        <label class="middle col-lg-2 col-md-3 col-sm-4 item-permission">
                                                            <input type="checkbox" name="permission_id[{{$key}}][]" value="{{$v->methods[0]}}|{{$v->uri}}" class="ace item_check" @if(in_array($v->methods[0].'|'.$v->uri, $permission)) checked="checked" @endif >
                                                            <span class="lbl"> {{$v->action['permission_name']}}</span>
                                                            <input type="hidden" name="name[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" value="{{$v->action['permission_name']}}">
                                                            <input type="hidden" name="menu[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" @if(isset($v->action['display_menu']))value="{{$v->action['display_menu']}}" @else value="0" @endif>
                                                            <input type="hidden" name="action_as[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" @if(isset($v->action['as']))value="{{$v->action['as']}}"@endif>
                                                            <input type="hidden" name="show_icon[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" @if(isset($v->action['display_icon_sub']))value="{{$v->action['display_icon_sub']}}"@endif>
                                                            <input type="hidden" name="group_name[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" value="{{$record['name']}}">
                                                            <input type="hidden" name="group_icon[{{$key}}][{{$v->methods[0]}}|{{$v->uri}}]" @if(isset($record['icon']))value="{{$record['icon']}}" @endif >
                                                        </label>
                                                    @endforeach
                                                    <div class="clearfix"></div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="mgt10">
                                            <input type="hidden" name="id" value="{{$id}}">
                                            {!! csrf_field() !!}
                                            <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary btn-sm">Lưu lại</button>
                                            <button type="reset" class="btn btn-sm">Bỏ qua</button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
