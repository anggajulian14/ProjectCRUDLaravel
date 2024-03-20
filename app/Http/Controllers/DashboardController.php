<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::count();

        return view('dashboard.dashboard', [
            'barang' => $barang,
        ]);
    }

    public function home(Request $request)
    {
        $barang = Barang::count();

        return view('dashboard.home', [
            'title' => 'Dashboard',
            'barang' => $barang,
        ]);
    }
}
