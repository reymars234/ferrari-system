<?php
// FILE: app/Http/Controllers/CarController.php — REPLACE ENTIRE FILE

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'latest');

        $query = Car::where('is_available', true);

        switch ($filter) {
            case 'iconic':
                // Iconic = most ordered (popular)
                $query->withCount('orders')->orderByDesc('orders_count');
                break;
            case 'cheapest':
                $query->orderBy('price', 'asc');
                break;
            case 'expensive':
                $query->orderByDesc('price');
                break;
            case 'rarest':
                // Rarest = highest price, lowest stock
                $query->orderByDesc('price')->orderBy('stock', 'asc');
                break;
            case 'latest':
            default:
                $query->orderByDesc('created_at');
                break;
        }

        $cars = $query->paginate(9)->withQueryString();

        // For AJAX requests, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'cars'   => $cars->map(fn($car) => [
                    'id'          => $car->id,
                    'name'        => $car->name,
                    'description' => $car->description,
                    'price'       => $car->price,
                    'price_fmt'   => number_format($car->price, 2),
                    'image'       => $car->image && file_exists(storage_path('app/public/cars/' . $car->image))
                                     ? asset('storage/cars/' . $car->image)
                                     : null,
                    'video_url'   => asset('videos/cars/' . $car->id . '.mp4'),
                    'buy_url'     => auth()->check()
                                     ? route('orders.create', $car->id)
                                     : route('login'),
                    'detail_url'  => route('cars.show', $car->id),
                    'is_auth'     => auth()->check(),
                ]),
                'pagination' => [
                    'current_page' => $cars->currentPage(),
                    'last_page'    => $cars->lastPage(),
                    'total'        => $cars->total(),
                ],
                'filter' => $filter,
            ]);
        }

        return view('shop', compact('cars', 'filter'));
    }

    public function show(Car $car)
    {
        abort_unless($car->is_available, 404);
        return view('cars.show', compact('car'));
    }
}