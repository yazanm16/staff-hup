<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('layouts.app');
    }
    public function admin (){
        return view('dashboard.admin');
    }
    public function employee (){
        return view('dashboard.employee');
    }
}
