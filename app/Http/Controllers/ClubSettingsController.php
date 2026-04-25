<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubSettingsController extends Controller
{
    public function __invoke()
    {
        return view('pages.club.club-settings');
    }

    public function update() {
        
    }
}
