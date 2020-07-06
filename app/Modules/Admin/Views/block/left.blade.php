<?php
use App\Library\PHPDev\FuncLib;
use App\Library\PHPDev\CGlobal;
?>
<div id="sidebar" class="sidebar sidebar-fixed responsive sidebar-scroll" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <a href="{{URL::route('admin.dashboard')}}" class="btn btn-success">
                <i class="ace-icon fa fa-signal"></i>
            </a>
            <a href="{{URL::route('admin.role')}}" class="btn btn-info">
                <i class="ace-icon fa fa-pencil"></i>
            </a>
            <a href="{{URL::route('admin.user')}}" class="btn btn-warning">
                <i class="ace-icon fa fa-users"></i>
            </a>
            <a href="" class="btn btn-danger">
                <i class="ace-icon fa fa-cogs"></i>
            </a>
        </div>
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>
            <span class="btn btn-info"></span>
            <span class="btn btn-warning"></span>
            <span class="btn btn-danger"></span>
        </div>
    </div>
    <ul class="nav nav-list">
        <li class="@if(Route::currentRouteName() == 'admin.dashboard') active @endif">
            <a href="{{URL::route('admin.dashboard')}}">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text"> Bảng điều khiển</span>
            </a>
            <b class="arrow"></b>
        </li>
        @if(isset($menu) && sizeof($menu) > 0)
            @foreach($menu as $key => $item)
                <?php
                $list_permission_as = [];
                $sub = isset($item['sub']) ? $item['sub'] : [];
                foreach($sub as $action){
                    if(isset($action['permission_as'])){
                        $list_permission_as[$action['permission_as']] = $action['permission_as'];
                    }
                }
                $permission_as = Route::currentRouteName();
                ?>
                <li @if(in_array($permission_as, $list_permission_as)) class="open" @endif>
                    <a href="" @if(!empty($sub))class="dropdown-toggle"@endif>
                        <i class="menu-icon {{ isset($item['icon']) ? $item['icon'] : '' }}"></i>
                        <b class="menu-text">{{ $key }}</b>
                        @if(!empty($sub))
                            <b class="arrow fa fa-angle-down"></b>
                        @endif
                    </a>
                    <b class="arrow"></b>
                    @if(!empty($sub))
                        <ul class="submenu">
                            @foreach($sub as $_sub)
                                <li class="@if(isset($_sub['permission_as']) && $permission_as == $_sub['permission_as']) active @endif">
                                    <a href="{{URL::route($_sub['permission_as'])}}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        {{ $_sub['permission_name'] }}
                                    </a>
                                    <b class="arrow"></b>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon"
           class="ace-icon fa fa-angle-double-left ace-save-state"
           data-icon1="ace-icon fa fa-angle-double-left"
           data-icon2="ace-icon fa fa-angle-double-right">
        </i>
    </div>
</div>
