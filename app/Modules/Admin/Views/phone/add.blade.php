<?php
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\ThumbImg;
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
                    <li class="active">@if($id==0)Thêm mới @else Sửa @endif Sản phẩm</li>
                </ul>
            </div>
            <div class="page-content">
                <div class="col-xs-12">
                    <div class="row">
                        @if(isset($error) && $error != '')
                            <div class="alert-admin alert alert-danger">{!! $error !!}</div>
                        @endif
                        <form class="form-horizontal paddingTop30" name="txtForm" action="" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12 mb-12">
                                    <div class="nav-tabs-horizontal nav-tabs-inverse" data-plugin="tabs">
                                        <ul class="nav nav-tabs nav-tabs-solid" role="tablist">
                                            <li class="nav-item active" role="presentation">
                                                <a class="nav-link active" data-toggle="tab" href="#tabNoiDung"
                                                   aria-controls="tabNoiDung" role="tab">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                    Nội dung
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content pt-10">
                                            <div class="tab-pane panelDetail active" id="tabNoiDung" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Tiêu đề<span>*</span></label>
                                                                <div class="controls">
                                                                    <input type="text" class="form-control input-sm" name="phone_title" value="@if(isset($data['phone_title'])){{$data['phone_title']}}@endif">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Ảnh</label>
                                                                <div class="controls">
                                                                    <a href="javascript:;"class="btn btn-primary link-button btn-sm" onclick="UploadAdmin.uploadMultipleImages(8);">Upload ảnh</a>
                                                                    <input name="image_primary" type="hidden" id="image_primary"  value="@if(isset($data['phone_image'])){{trim($data['phone_image'])}}@endif">
                                                                </div>
                                                                <!--Hien Thi Anh-->
                                                                <ul id="sys_drag_sort" class="ul_drag_sort">
                                                                    @if(isset($news_image_other))
                                                                        @foreach($news_image_other as $k=>$v)
                                                                            <li id="sys_div_img_other_{{$k}}">
                                                                                <div class="div_img_upload">
                                                                                    <img src="{{$v['src_img_other']}}" height="80">
                                                                                    <input type="hidden" id="sys_img_other_{{$k}}" name="img_other[]" value="{{$v['img_other']}}" class="sys_img_other">
                                                                                    <div class='clear'></div>
                                                                                    <input type="radio" id="checked_image_{{$k}}" name="checked_image" value="{{$k}}"
                                                                                           @if(isset($news_image) && ($news_image == $v['img_other'])) checked="checked" @endif
                                                                                           onclick="UploadAdmin.checkedImage('{{$v['img_other']}}','{{$k}}');">
                                                                                    <label for="checked_image_{{$k}}" style='font-weight:normal'>Ảnh đại diện</label>
                                                                                    <br/>
                                                                                    <a href="javascript:void(0);" id="sys_delete_img_other_{{$k}}" onclick="UploadAdmin.removeImage('{{$k}}', '{{$data['phone_id']}}', '{{$v['img_other']}}', '8');">Xóa ảnh</a>
                                                                                    <span style="display: none"><b>{{$k}}</b></span>
                                                                                </div>
                                                                            </li>
                                                                            @if(isset($news_image) && $news_image == $v['img_other'])
                                                                                <input type="hidden" id="sys_key_image_primary" name="sys_key_image_primary" value="{{$k}}">
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        <input type="hidden" id="sys_key_image_primary" name="sys_key_image_primary" value="-1">
                                                                    @endif

                                                                </ul>
                                                                <input name="list1SortOrder" id ='list1SortOrder' type="hidden" />
                                                                <!--Hien Thi Anh-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Nội dung</label>
                                                        <div class="controls">
                                                            <button type="button" onclick="UploadAdmin.getInsertImageContent(8, 'open')" class="btn btn-primary btn-sm">Chèn ảnh vào nội dung</button>
                                                        </div>
                                                        <div class="controls">
                                                            <textarea class="form-control input-sm" name="phone_content">@if(isset($data['phone_content'])){{stripslashes($data['phone_content'])}}@endif</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Nổi bật</label>
                                                        <div class="controls">
                                                            <select class="form-control input-sm" name="phone_focus">
                                                                {!! $optionFocus !!}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Trạng thái</label>
                                                        <div class="controls">
                                                            <select class="form-control input-sm" name="phone_status">
                                                                {!! $optionStatus !!}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Meta description</label>
                                                        <div class="controls">
                                                            <textarea class="form-control input-sm" name="meta_description">@if(isset($data['meta_description'])){{$data['meta_description']}}@endif</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                        <div class="panel-footer clearfix">
                                            <div class="form-inline float-right">
                                                <div class="form-row">
                                                    {!! csrf_field() !!}
                                                    <input type="hidden" id="id_hiden" name="id_hiden" value="{{$id}}"/>
                                                    <button type="submit" name="txtSubmit" id="buttonSubmit" class="btn btn-primary btn-sm">Lưu lại</button>
                                                    <button type="reset" class="btn btn-sm">Bỏ qua</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Popup Upload Img-->
    <div class="modal fade" id="sys_PopupUploadImgOtherPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Upload ảnh</h4>
                </div>
                <div class="modal-body">
                    <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                        <div class="form_group">
                            <div id="sys_show_button_upload">
                                <div id="sys_mulitplefileuploader" class="btn btn-primary">Upload ảnh</div>
                            </div>
                            <div id="status"></div>

                            <div class="clearfix"></div>
                            <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                                <div id="div_image"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Popup Upload Img-->

    <!--Popup chen anh vào noi dung-->
    <div class="modal fade" id="sys_PopupImgOtherInsertContent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Click ảnh để chèn vào nội dung</h4>
                </div>
                <div class="modal-body">
                    <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                        <div class="form_group">
                            <div class="clearfix"></div>
                            <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                                <div id="div_image" class="float_left"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Popup chen anh vào noi dung-->

    <script>
        CKEDITOR.replace('phone_content');
        //Keo Tha Anh
        jQuery("#sys_drag_sort").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder });
        function saveOrder() {
            var data = jQuery("#sys_drag_sort li div span").map(function() { return jQuery(this).children().html(); }).get();
            jQuery("input[name=list1SortOrder]").val(data.join(","));
        };
        //Chen Anh Vao Noi Dung
        function insertImgContent(src){
            CKEDITOR.instances.phone_content.insertHtml('<img src="'+src+'"/>');
        }
        insertExtLinkContent();
        function insertExtLinkContent(){
            $('.extLinkClick ul li').click(function(){
                var text = $(this).html();
                CKEDITOR.instances.phone_content.insertHtml(text);
            });
        }
    </script>
@stop

