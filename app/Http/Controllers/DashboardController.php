<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Base incident query for filtering
        $incidentQuery = DB::table('incidents');

        if ($request->filled('severity')) {
            $incidentQuery->where('severity', $request->severity);
        }
        if ($request->filled('status')) {
            $incidentQuery->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $incidentQuery->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $incidentQuery->where('title', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $incidentQuery->whereBetween('incident_date', [$request->date_from, $request->date_to]);
        }

        // 1. SUMMARY METRICS CARDS
        $totalIncidents = (clone $incidentQuery)->count();
        $openIncidents = (clone $incidentQuery)->where('status', 'OPEN')->count();
        $criticalIncidents = (clone $incidentQuery)->where('severity', 'CRITICAL')->count();
        $resolvedIncidents = (clone $incidentQuery)->where('status', 'RESOLVED')->count();

        // 2. CRITICAL INCIDENT PANEL
        $criticalPanelIncidents = DB::table('incidents')
            ->leftJoin('users', 'incidents.assigned_to', '=', 'users.id')
            ->where('severity', 'CRITICAL')
            ->where('status', '!=', 'RESOLVED')
            ->select('incidents.*', 'users.name as assigned_operator_name')
            ->orderBy('incidents.incident_date', 'desc')
            ->limit(5)
            ->get();

        // 3. RECENT INCIDENTS TABLE
        $recentIncidents = (clone $incidentQuery)
            ->leftJoin('incident_categories', 'incidents.category_id', '=', 'incident_categories.id')
            ->leftJoin('users', 'incidents.assigned_to', '=', 'users.id')
            ->select(
                'incidents.*',
                'incident_categories.name as category_name',
                'users.name as assigned_operator_name'
            )
            ->orderBy('incidents.incident_date', 'desc')
            ->paginate(10);

        // 4. INCIDENT STATUS SUMMARY CHART
        $statusChart = DB::table('incidents')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        // 5. SEVERITY DISTRIBUTION CHART
        $severityChart = DB::table('incidents')
            ->select('severity', DB::raw('count(*) as total'))
            ->groupBy('severity')
            ->pluck('total', 'severity');

        // 6. RECENT AUDIT LOGS
        $recentAuditLogs = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
            ->select('audit_logs.*', 'users.name as user_name')
            ->orderBy('audit_logs.created_at', 'desc')
            ->limit(10)
            ->get();

        // For filter dropdowns
        $categories = DB::table('incident_categories')->select('id', 'name')->get();

        return view('pages.opssight.dashboard', compact(
            'totalIncidents',
            'openIncidents',
            'criticalIncidents',
            'resolvedIncidents',
            'criticalPanelIncidents',
            'recentIncidents',
            'statusChart',
            'severityChart',
            'recentAuditLogs',
            'categories'
        ));
    }
}
