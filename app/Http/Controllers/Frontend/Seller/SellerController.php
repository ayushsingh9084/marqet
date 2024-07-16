<?php

namespace App\Http\Controllers\Frontend\Seller;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class SellerController extends Controller
{

    public function index($id = null)
    {
        // dd($request->all());
        return view('pages.seller.form', ['id' => $id ]); // 'home' is the name of the Blade view file
    }
    public function businessDetails($id = null)
    {
        // dd($request->all());
        return view('pages.seller.business-details', ['id' => $id]); // 'home' is the name of the Blade view file
    }
}
