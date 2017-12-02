<?php

namespace App\Http\Controllers;

class MainController extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function test(){
        return 'test';
    }
}