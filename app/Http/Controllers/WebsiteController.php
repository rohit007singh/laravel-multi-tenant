<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TenantDatabaseService;
use App\Models\Website;
use Illuminate\Support\Facades\Auth;

class WebsiteController extends Controller
{
    public function index()
    {
        // Configure the tenant connection for the current user
        app(TenantDatabaseService::class)->configureTenantConnection(Auth::user());

        $websites = Website::all();

        return view('dashboard', compact('websites'));
    }

    // add create method
    public function create()
    {
        return view('websites.create');
    }


    public function store(Request $request)
    {
        // Configure the tenant connection for the current user
        app(TenantDatabaseService::class)->configureTenantConnection(Auth::user());
        Website::create($request->all());

        return redirect()->route('websites.index');
    }
}
