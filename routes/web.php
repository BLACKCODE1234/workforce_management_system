<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login', 'auth-views.login');
Route::view('/signup', 'auth-views.signup');
