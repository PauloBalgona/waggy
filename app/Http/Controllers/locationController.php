<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\User;
use App\Models\FriendRequest;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::orderBy('region')->orderBy('province')->get();
        $contacts = auth()->user()->friends()->get();
        return view('location.location', compact('locations', 'contacts'));
    }
}