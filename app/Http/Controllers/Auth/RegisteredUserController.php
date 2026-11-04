<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    /** Public registration is disabled. */
    public function create(): never
    {
        abort(404);
    }

    public function store(Request $request): never
    {
        abort(404);
    }
}
