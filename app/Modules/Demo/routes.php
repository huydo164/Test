<?php

$namespace = '\App\Modules\Demo\Controllers';

Route::group(['middleware' => ['web'] , 'prefix' => '/', 'namespace' => $namespace], function (){

    Route::get('403', array('as' => 'page.403', 'uses' => 'BaseDemoController@page403'));
    Route::get('404', array('as' => 'page.404', 'uses' => 'BaseDemoController@page404'));

    Route::get('/', array( 'uses' => 'DemoController@listView'));

    Route::get('The/{name}-{id}.html', array('as' => 'side.detailDemo', 'uses' => 'DemoController@detailDemo'))->where('name', '[A-Z0-9a-z_\-]+')->where('id' , '[0-9]+');
});
