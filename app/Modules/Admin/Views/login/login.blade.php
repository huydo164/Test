<?php
use App\Library\PHPDev\CGlobal;
?>
@extends('Admin::login.html')
@section('content')
    <div class="page-login">
        <div class="wrapp-page-login">
            <div class="box-title-login">
                <span class="cms">CMS</span>
                <span class="white">Control Panel</span>
                <div class="copyright">&copy; @if(CGlobal::domain){{ucwords(CGlobal::domain)}}@endif</div>
            </div>
            <div class="box-login">
                <div class="form-login">
                    <form method="post" action="" class="formSendLogin">
                        <div class="line-title-form">Vui lòng nhập thông tin</div>
                        @if(isset($error) && $error != '')
                            <div class="alert alert-danger">{{$error}}</div>
                        @endif
                        <div class="form-group">
                            <div class="item-line">
                                <input type="text" name="name" class="form-control" placeholder="Tên đăng nhập" @if(isset($name)) value="{{$name}}" @endif>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="item-line">
                                <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                            </div>
                        </div>
                        {!! csrf_field() !!}
                        <button type="submit" class="btn btn-primary btnLogin">
                            <span class="txt-login">Đăng nhập</span>
                        </button>
                        <a rel="nofollow" href="javascript:void(0)" class="forgotpass">Bạn quên mật khẩu?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
