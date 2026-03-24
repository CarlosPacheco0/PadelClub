<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubAdminController extends Controller
{
    public function __invoke()
    {
        return view('pages.admin.dashboard');
    }
}
