<?php

namespace App\Controllers;

use Core\Classes\Request;

class ErrorController extends Controller
{
    public function error403(Request $request)
    {
        return view('errors.403');
    }

    public function error404(Request $request)
    {
        return view('errors.404');
    }
}