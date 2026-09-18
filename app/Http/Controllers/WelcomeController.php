<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Work;

class WelcomeController extends Controller
{
    public function index()
    {
        $totalCustomer = Customer::count();
        $works = Work::withCount('customers')->get();

        return view('welcome', compact('totalCustomer', 'works'));
    }
}
