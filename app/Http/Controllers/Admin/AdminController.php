<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Utils;

class AdminController extends Controller
{
    use Utils;

    public function welcome()
    {
        return view('welcome');
    }
}
