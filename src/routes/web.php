<?php
/**
 * Created by PhpStorm.
 * Filename: web.php
 * Description:
 * User: orhanbhr
 * Date: 4.11.2020
 * Time: 15:21
 */

Route::group(['namespace' => 'orhanbhr\IddaaBot\Controllers'], function () {
    Route::get('matches', 'IddaaBotController@index')->name('iddaabot');
});
