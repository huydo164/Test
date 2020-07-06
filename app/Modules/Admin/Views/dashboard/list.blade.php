@extends('Admin::layout.html')
@section('header')
    @include('Admin::block.header')
@stop
@section('left')
    @include('Admin::block.left')
@stop
@section('content')
    <div class="main-content">
        <div class="notification-global">Quản trị nội dung website</div>
        <div class="content-global">
            @if($messages != '')
                <div class="col-lg-12 messages-dash">
                    {!! $messages !!}
                </div>
            @endif
            @if(isset($menu) && sizeof($menu) > 0)
                @foreach($menu as $key => $item)
                    <?php $sub = isset($item['sub']) ? $item['sub'] : []; ?>
                    @if(!empty($sub))
                        @foreach($sub as $_sub)
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-6">
                                <a href="{{URL::route($_sub['permission_as'])}}">
                                    <div class="boder-item padding10 text-center">
                                        @if(isset($_sub['permission_icon']) && $_sub['permission_icon'] != '')
                                            <i class="icon-4x {{$_sub['permission_icon']}}"></i>
                                        @endif<br>
                                        <span>{{ $_sub['permission_name'] }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
    </div>
@stop
