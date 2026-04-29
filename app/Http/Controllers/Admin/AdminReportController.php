<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index()
    {
        $salesByMonth = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->whereIn('status', ['processing','delivered'])
            ->groupBy('year','month')
            ->orderByDesc('year')->orderByDesc('month')
            ->take(12)->get();

        $topCars = Order::select('car_id', DB::raw('COUNT(*) as orders_count'), DB::raw('SUM(total_price) as revenue'))
            ->with('car')
            ->whereIn('status', ['processing','delivered'])
            ->groupBy('car_id')
            ->orderByDesc('orders_count')
            ->take(5)->get();

        return view('admin.reports.index', compact('salesByMonth', 'topCars'));
    }
}