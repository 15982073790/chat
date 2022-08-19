<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 2020/3/4
 * Time: 16:48
 */

use think\Route;

Route::get('api/group/:business_id', 'api/Group/getGroup');
