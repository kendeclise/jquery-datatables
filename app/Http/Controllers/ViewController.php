<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function datatableBasicView()
    {
        return view('datatables.basic');
    }

    public function dataTableServerSideView()
    {
        $categories = Category::orderBy('name')->get();
        return view('datatables.serverside', compact('categories'));
    }
}
