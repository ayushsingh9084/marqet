<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        // dd($request->all());

        // dd($devices->data->unknown_count);
        return view('pages.admin.dashboard'); // 'home' is the name of the Blade view file
    }
}
