<?php
namespace App\Http\Controllers;

use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Car::where('is_available', true)->take(6)->get();
        return view('home', compact('featuredCars'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }
}