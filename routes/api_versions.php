<?php


//this file is used to load all api versions routes
//i can add more api versions in the future like v2 and v3


use Illuminate\Support\Facades\Route;




Route::middleware('api')->prefix('api/v1')
    ->group(base_path('routes/api/v1.php'));
