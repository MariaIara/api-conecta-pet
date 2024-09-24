<?php

use App\Core\Router;

/* ~~~ Application Routes 🚦 ~~~  */

Router::get('/', function(){
    return view('example');
});