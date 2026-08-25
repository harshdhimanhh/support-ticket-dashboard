<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()
            ->tickets()
            ->latest()
            ->paginate(10);

        return view('customer.dashboard', compact('tickets'));
    }
}
