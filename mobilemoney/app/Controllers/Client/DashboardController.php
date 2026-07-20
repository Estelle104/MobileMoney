<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{

    public function index()
    {
        return view('client/dashboard');
    }

}