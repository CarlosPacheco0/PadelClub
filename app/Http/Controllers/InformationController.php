<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function __invoke()
    {
        return view('pages.information');
    }
}
