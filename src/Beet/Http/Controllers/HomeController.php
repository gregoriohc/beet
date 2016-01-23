<?php

namespace Gregoriohc\Beet\Http\Controllers;

use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function index()
    {
        return Redirect::route('home');
    }
}