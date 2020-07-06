<?php

$namespace = '\App\Modules\Admin\Controllers';

Route::group(['middleware' => ['web'], 'prefix' => '/' , 'namespace' => $namespace], function (){
    Route::get('login/{url?}', array('as' => 'login','uses' => 'LoginController@getLogin'));
    Route::post('login/{url?}', array('as' => 'login','uses' => 'LoginController@postLogin'));
    Route::get('logout', array('as' => 'logout','uses' => 'LoginController@logout'));
    Route::post('ajax/upload', array('as' => 'ajax.upload','uses' => 'AjaxUploadController@upload'));
});

Route::group(['middleware' => ['web', 'checkPermission'], 'prefix'=>'admin', 'namespace' => $namespace, 'group' => '4', 'group_name' => 'Nội dung', 'display_icon' => 'fa fa-desktop' ], function () {

    Route::get('product', array('as' => 'admin.product', 'uses' => 'ProductController@listView', 'permission_name' => 'sản phẩm', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-globe'));
    Route::get('product/edit/{id?}', array('as' => 'admin.product_edit', 'uses' => 'ProductController@getItem', 'permission_name' => 'Chi tiết sản phẩm'))->where('id', '[0-9]+');
    Route::post('product/edit/{id?}', array('as' => 'admin.product_edit', 'uses' => 'ProductController@postItem', 'permission_name' => 'Sửa sản phẩm'))->where('id', '[0-9]+');
    Route::post('product/delete', array('as' => 'admin.product_delete', 'uses' => 'ProductController@delete', 'permission_name' => 'Xóa sản phẩm'));

    Route::get('photo', array('as' => 'admin.photo' , 'uses' => 'PhotoController@listView', 'permission_name' => 'sản phẩm 1', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-globe'));
    Route::get('photo/edit/{id?}', array('as' => 'admin.photo_edit' , 'uses' => 'PhotoController@getItem', 'permission_name' => ' chi tiết sản phẩm 1'))->where('id', '[0-9]+');
    Route::post('photo/edit/{id?}', array('as' => 'admin.photo_edit', 'uses' => 'PhotoController@postItem', 'permission_name' => ' sửa sản phẩm 1'))->where('id', '[0-9]+');
    Route::post('photo/delete', array('as' => 'admin.photo_delete', 'uses' => 'PhotoController@delete', 'permission_name' => 'xóa sản phẩm 1'));

    Route::get('phone', array('as' => 'admin.phone', 'uses' => 'PhoneController@listview' , 'permission_name' => 'sản phẩm 2', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-globe'));
    Route::get('phone/edit/{id?}' , array('as' => 'admin.phone_edit', 'uses' => 'PhoneController@getItem', 'permission_name' => 'chi tiết sản phẩm 2'))->where('id', '[0-9]+');
    Route::post('phone/edit/{id?}', array('as' =>'admin.phone_edit', 'uses' => 'PhoneController@postItem', 'permission_name' => 'sửa sản phẩm 2'))->where('id', '[0-9]+');
    Route::post('phone/delete' , array('as' => 'admin.phone_delete', 'uses' => 'PhoneController@delete', 'permission_name' => 'xóa sản phẩm 2'));

    Route::get('demo', array('as' => 'admin.demo' , 'uses'  => 'DemoController@listView', 'permission_name' => 'Danh sách thử', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-globe'));
    Route::get('demo/edit/{id?}', array('as' => 'admin.demo_edit' , 'uses' => 'DemoController@getItem', 'permission_name' => 'Chi tiết thử'))->where('id', '[0-9]+');
    Route::post('demo/edit/{id?}', array('as' => 'admin.demo_edit' , 'uses' => 'DemoController@postItem', 'permission_name' => 'Sử thử'))->where('id', '[0-9]+');
    Route::post('demo/delete' , array('as' => 'admin.demo_delete' , 'uses' => 'DemoController@delete', 'permission_name' => 'Xóa thử'));

    Route::get('statics', array('as' => 'admin.statics' , 'uses' => 'StaticsController@listView', 'permission_name' => 'sản phẩm 3', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-globe'));
    Route::get('statics/edit/{id?}', array('as' => 'admin.statics_edit' , 'uses' => 'StaticsController@getItem', 'permission_name' => 'chi sản phẩm 3'))->where('id' , '[0-9]+');
    Route::post('statics/edit/{id?}', array('as' => 'admin.statics_edit' , 'uses' => 'StaticsController@postItem', 'permission_name' => 'sửa sản phẩm 3'))->where('id', '[0-9]+');
    Route::post('statics/delete', array('as' => 'admin.statics_delete', 'uses' => 'StaticsController@delete', 'permission_name' => 'xóa sản phẩm 3'));


});

Route::group(['middleware' => ['web', 'checkPermission'], 'prefix' => 'admin', 'namespace' => $namespace , 'group' => '5', 'group_name' => 'Hệ thống', 'display_icon' => 'fa fa-tag'], function (){

    Route::get('dashboard', array('as' => 'admin.dashboard', 'uses' => 'DashBoardController@listView', 'permission_name' => 'Bảng điều khiển'));

    Route::get('role', array('as' => 'admin.role', 'uses' => 'UserRoleController@listView' , 'permission_name' => 'Danh sách quyền', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-gears'));
    Route::get('role/edit/{id?}' , array('as' => 'admin.role_edit', 'uses' => 'UserRoleController@getItem' , 'permission_name' => 'Chi tiết quyền'))->where('id', '[0-9]+');
    Route::post('role/edit/{id?}', array('as' => 'admin.role_edit', 'uses' => 'UserRoleController@postItem', 'permission_name' => 'Sửa quyền'))->where('id', '[0-9]+');
    Route::get('role/permission/{id?}', array('as' => 'admin.role_permission', 'uses' => 'UserRoleController@permission', 'permission_name' => 'Chi tiết quyền'))->where('id', '[0-9]+');
    Route::post('role/permission/{id?}', array('as' => 'admin.role_permission_save' , 'uses' => 'UserRoleController@permissionSave', 'permission_name' => 'Sửa quyền'))->where('id', '[0-9]+');
    Route::post('role/delete', array('as' => 'admin.role_delete', 'uses' => 'UserRoleController@delete', 'permission_name' => 'Xóa quyền'));

    Route::get('roleGroup', array('as' => 'admin.roleGroup', 'uses' => 'UserRoleGroupController@listView', 'permission_name' => 'Danh sách nhóm quyền', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-group'));
    Route::get('roleGroup/edit/{id?}', array('as' => 'admin.roleGroup_edit', 'uses' => 'UserRoleGroupController@getItem', 'permission_name' => 'Chi tiết nhóm quyền'))->where('id', '[0-9]+');
    Route::post('roleGroup/edit/{id?}', array('as' => 'admin.roleGroup_edit','uses' => 'UserRoleGroupController@postItem', 'permission_name'=>'Sửa nhóm quyền'))->where('id', '[0-9]+');
    Route::post('roleGroup/delete', array('as' => 'admin.roleGroup_delete', 'uses' => 'UserRoleGroupController@delete', 'permission_name' => 'Xóa quyền' ));

    Route::get('users', array('as' => 'admin.user', 'uses' => 'UserController@listView', 'permission_name' => 'Danh sách người dùng', 'display_menu' => 1, 'display_icon_sub' => 'fa fa-user'));
    Route::get('users/edit/{id?}', array('as' => 'admin.user_edit', 'uses' => 'UserController@getItem', 'permission_name' => 'Chi tiết người dùng'))->where('id', '[0-9]+');
    Route::post('users/edit/{id?}', array('as' => 'admin.user_edit', 'uses' => 'UserController@postItem', 'permission_name' => 'Sửa người dùng'))->where('id', '[0-9]+');
    Route::post('users/delete', array('as' => 'admin.user_delete', 'uses' => 'UserController@delete', 'permission_name' => 'Xóa người dùng'));
});

