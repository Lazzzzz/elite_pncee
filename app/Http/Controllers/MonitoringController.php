<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get total users
        $totalUsers = DB::table('users')->count();

        // Get search requests today
        $searchesToday = SearchHistory::whereDate('created_at', today())->count();

        // Get average response time
        $avgResponseTime = SearchHistory::avg('execution_time');

        // Format to 2 decimal places with seconds
        $avgResponseTime = number_format($avgResponseTime ?? 0, 2) . 's';

        // Get active admins (you might need to adjust this based on your user roles system)
        $activeAdmins = 3; // Placeholder value

        return view('monitoring', compact(
            'totalUsers',
            'searchesToday',
            'avgResponseTime',
            'activeAdmins'
        ));
    }
}
