<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $user = $request->user();
        $user->loadMissing('menuPermissions');
        if (!$user->hasMenuPermission('home', 'view')) {
            abort(403, 'Akses ditolak.');
        }

        $month = $request->query('month');
        $monthDate = $month ? Carbon::createFromFormat('Y-m', $month)->startOfMonth() : Carbon::now()->startOfMonth();
        $start = $monthDate->copy()->startOfMonth();
        $end = $monthDate->copy()->endOfMonth();

        $visitQuery = PageVisit::query()
            ->whereBetween('visited_on', [$start->toDateString(), $end->toDateString()]);

        $visitCount = $visitQuery
            ->selectRaw('COUNT(DISTINCT COALESCE(session_id, ip_address)) as total')
            ->value('total') ?? 0;

        $contactCount = ContactMessage::query()
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $visitSeries = PageVisit::query()
            ->select('visited_on', DB::raw('COUNT(DISTINCT COALESCE(session_id, ip_address)) as total'))
            ->whereBetween('visited_on', [$start->toDateString(), $end->toDateString()])
            ->groupBy('visited_on')
            ->orderBy('visited_on')
            ->pluck('total', 'visited_on');

        $contactSeries = ContactMessage::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'day');

        $daysInMonth = $start->daysInMonth;
        $labels = [];
        $visitValues = [];
        $contactValues = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $current = $start->copy()->day($day)->toDateString();
            $labels[] = (string) $day;
            $visitValues[] = (int) ($visitSeries[$current] ?? 0);
            $contactValues[] = (int) ($contactSeries[$current] ?? 0);
        }

        return response()->json([
            'month' => $start->format('Y-m'),
            'visits' => $visitCount,
            'contacts' => $contactCount,
            'series' => [
                'labels' => $labels,
                'visits' => $visitValues,
                'contacts' => $contactValues,
            ],
        ]);
    }
}
