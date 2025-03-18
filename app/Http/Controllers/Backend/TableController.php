<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TableController extends Controller
{
    public function index () {
        return Inertia::Render('Tables/Index');
    }
}
