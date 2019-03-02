<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function mainView()
    {
        $categories = Category::orderBy('name')->get();
        return view('datatables.main', compact('categories'));
    }
}
