<?php

namespace App\Http\Controllers;

// for pagination
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
// for request handling
use Illuminate\Http\Request;
use Livewire\WithPagination;

class HomeController extends Controller
{
    use WithPagination;

    public function notFound()
    {
        return view('404');
    }

    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        return view('home');
    }
}
