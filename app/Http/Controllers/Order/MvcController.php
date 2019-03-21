<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MvcController extends Controller
{
    //

    public function test1()
    {
        $data = [
            'title' => 'MVC-Test'
        ];
        return view('index.bst',$data);
    }

    public function bst()
    {
        $data = [
            'title' => 'MVC-Test'
        ];
        return view('index.bst',$data);
    }
}
