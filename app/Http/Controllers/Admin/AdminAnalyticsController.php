<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function getStatusData()
    {
        $data = User::where('is_admin', false)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        $total = $data->sum('count');

        $formattedData = $data->map(function ($item) use ($total) {
            return [
            'status' => ucfirst($item->status),
            'count' => $item->count,
            'percentage' => $total > 0 ? round(($item->count / $total) * 100, 2) : 0
            ];
        });

        return response()->json($formattedData);
    }

    public function getGrowthData(Request $request)
    {
        $filter = $request->query('filter', 'monthly');
        $query = User::where('is_admin', false);

        switch ($filter) {
            case 'daily':
                $data = $query->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count')
                )
                    ->where('created_at', '>=', Carbon::now()->subDays(30))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            case 'weekly':
                $data = $query->select(
                    DB::raw('YEARWEEK(created_at) as week'),
                    DB::raw('MIN(DATE(created_at)) as date'),
                    DB::raw('count(*) as count')
                )
                    ->where('created_at', '>=', Carbon::now()->subWeeks(12))
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
                break;

            case 'monthly':
            default:
                $data = $query->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('MIN(DATE(created_at)) as date'),
                    DB::raw('count(*) as count')
                )
                    ->where('created_at', '>=', Carbon::now()->subMonths(12))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
                break;
        }

        return response()->json($data);
    }
}