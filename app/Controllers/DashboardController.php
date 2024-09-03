<?php namespace App\Controllers;

use App\Models\MataKuliahModel;

class DashboardController extends BaseController
{
    public function index() {
        return view('dashboard/index');
    }
}