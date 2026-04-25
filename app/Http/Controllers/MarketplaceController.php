<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function __invoke() 
    {
        return view(
            'pages.martketplace'
        );
    }
}
