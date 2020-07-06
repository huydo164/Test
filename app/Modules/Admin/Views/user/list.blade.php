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
				<li class="active">Quản lý quyền truy cập</li>
			</ul>
		</div>
		<div class="page-content">
			<div class="row">
				<div class="col-xs-12">
					<div class="panel panel-info @if(isset($search['submit']) && $search['submit'] == CGlobal::status_show) act @endif">
						<form id="frmSearch" method="GET" action="" class="frmSearch" name="frmSearch">
							<div class="panel-body panel-search">
								<div class="form-group col-lg-2">
									<label class="control-label">Từ khóa</label>
									<div>
										<input type="text" class="form-control input-sm" name="user_name" @if(isset($search['user_name']) && $search['user_name'] !='')value="{{$search['user_name']}}"@endif>
									</div>
								</div>
								<div class="form-group col-lg-2">
									<label class="control-label">Chức vụ</label>
									<div>
										<select name="user_rid" class="form-control input-sm">
											{!! $optionRoleGroup !!}
										</select>
									</div>
								</div>
								<div class="form-group col-lg-2">
									<label class="control-label">Trạng thái</label>
									<div>
										<select name="user_status" class="form-control input-sm">
											{!! $optionStatus !!}
										</select>
									</div>
								</div>
							</div>
							<div class="panel-footer text-right">
								<a class="btn btn-default btn-sm" class="reset" href="{{route('admin.user')}}"><i class="fa fa-recycle"></i>Bỏ lọc</a>
								<a class="btn btn-danger btn-sm" href="{{FuncLib::getBaseUrl()}}admin/users/edit"><i class="ace-icon fa fa-plus-circle"></i>Thêm mới</a>
								<button class="btn btn-primary btn-sm" type="submit" name="submit" value="1"><i class="fa fa-search"></i> Tìm kiếm</button>
								<a href="javascript:void(0)" title="Xóa item" id="deleteMoreItem" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Xóa</a>
								<span class="btn btn-warning btn-sm clickSearchDrop">Mở rộng</span>
							</div>
						</form>
					</div>
					@if(isset($messages) && $messages != '')
						{!! $messages !!}
					@endif
					@if(sizeof($data) > 0)
						@if($total>0)
							<div class="show-bottom-info">
								<div class="total-rows">Tổng số: <b>{{$total}}</b></div>
								<div class="list-item-page">
									<div class="showListPage">{!! $paging !!}</div>
								</div>
							</div>
						@endif
						<br>
						<form id="formListItem" method="POST" action="{{FuncLib::getBaseUrl()}}admin/users/delete" class="formListItem" name="txtForm">
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
									<th width="10%">Họ và tên</th>
									<th width="10%">Nhóm quyền</th>
									<th width="5%">Mã</th>
									<th width="10%">Tên đăng nhập</th>
									<th width="10%">Email</th>
									<th width="10%">SĐT</th>
									<th width="5%">Ngày tạo</th>
									<th width="5%">Trạng thái</th>
									<th width="5%">Action</th>
								</tr>
								</thead>
								<tbody>
								@foreach($data as $k=>$item)
									<tr>
										<td>{{$k+1}}</td>
										<td>
											<label class="pos-rel">
												<input class="ace checkItem" name="checkItem[]" value="{{$item['user_id']}}" type="checkbox">
												<span class="lbl"></span>
											</label>
										</td>
										<td>{{$item['user_full_name']}}</td>
										<td>{{isset($arrRoleGroup[$item['user_rid']]) ? $arrRoleGroup[$item['user_rid']] : ''}}</td>
										<td>#{{$item['user_id']}}</td>
										<td>{{$item['user_name']}}</td>
										<td>{{$item['user_mail']}}</td>
										<td>{{$item['user_phone']}}</td>
										<td>{{date('d/m/Y', $item['user_created'])}}</td>
										<td>
											@if($item['user_status'] == '1')
												<i class="fa fa-check fa-admin green"></i>
											@else
												<i class="fa fa-remove fa-admin red"></i>
											@endif
										</td>
										<td>
											<a href="{{FuncLib::getBaseUrl()}}admin/users/edit/{{$item['user_id']}}" title="Cập nhật">
												<i class="fa fa-edit fa-admin"></i>
											</a>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							@if($total>0)
								<div class="show-bottom-info">
									<div class="total-rows">Tổng số: <b>{{$total}}</b></div>
									<div class="list-item-page">
										<div class="showListPage">{!! $paging !!}</div>
									</div>
								</div>
							@endif
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
