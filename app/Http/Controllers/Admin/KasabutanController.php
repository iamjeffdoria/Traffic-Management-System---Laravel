<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class KasabutanController extends Controller
{
    public function selling()
    {
        return view('admin.kasabutan-selling');
    }

    public function lostItem()
    {
        return view('admin.kasabutan-lost-item');
    }
}