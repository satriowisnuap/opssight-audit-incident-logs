<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentController extends Controller
{
    /**
     * Display a listing of incidents.
     */
    public function index(Request $request)
    {
        $query = DB::table('incidents')
            ->leftJoin(
                'incident_categories',
                'incidents.category_id',
                '=',
                'incident_categories.id'
            )
            ->leftJoin(
                'users as operators',
                'incidents.assigned_to',
                '=',
                'operators.id'
            )
            ->select(
                'incidents.*',
                'incident_categories.name as category_name',
                'operators.name as operator_name'
            );

        /**
         * SEARCH FILTER
         */
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'incidents.title',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'incidents.id',
                    $request->search
                );
            });
        }

        /**
         * SEVERITY FILTER
         */
        if ($request->severity) {
            $query->where(
                'incidents.severity',
                $request->severity
            );
        }

        /**
         * STATUS FILTER
         */
        if ($request->status) {
            $query->where(
                'incidents.status',
                $request->status
            );
        }

        /**
         * CATEGORY FILTER
         */
        if ($request->category) {
            $query->where(
                'incidents.category_id',
                $request->category
            );
        }

        /**
         * GET INCIDENTS
         */
        $incidents = $query
            ->orderBy('incidents.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        /**
         * GET CATEGORIES
         */
        $categories = DB::table('incident_categories')
            ->orderBy('name')
            ->get();

        return view(
            'pages.opssight.incidents.index',
            [
                'incidents' => $incidents,
                'categories' => $categories,
            ]
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $categories = DB::table('incident_categories')
            ->orderBy('name')
            ->get();

        $operators = DB::table('users')
            ->orderBy('name')
            ->get();

        return view(
            'pages.opssight.incidents.create',
            [
                'categories' => $categories,
                'operators' => $operators,
            ]
        );
    }

   /**
 * Store new incident.
 */
public function store(Request $request)
{
    /**
     * VALIDATION
     */
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'severity' => 'required',
        'status' => 'required',
        'category_id' => 'required',
        'incident_date' => 'required',
    ]);

    /**
     * INSERT INCIDENT
     */
    $incidentId = DB::table('incidents')->insertGetId([
        'title' => $request->title,
        'description' => $request->description,
        'severity' => $request->severity,
        'status' => $request->status,
        'category_id' => $request->category_id,
        'assigned_to' => $request->assigned_to,
        'reported_by' => auth()->id(),
        'incident_date' => $request->incident_date,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

/**
 * CREATE AUDIT LOG
 */
DB::table('audit_logs')->insert([
    'user_id' => auth()->id(),
    'action' => 'CREATE_INCIDENT',
    'table_name' => 'incidents',
    'record_id' => $incidentId,
    'old_values' => null,
    'new_values' => json_encode([
        'message' => 'Incident created',
    ]),
    'ip_address' => $request->ip(),
    'created_at' => now(),
]);

    /**
     * REDIRECT
     */
    return redirect()
        ->route('incidents.show', $incidentId)
        ->with(
            'success',
            'Incident created successfully.'
        );
}

    /**
     * Show incident detail.
     */
   public function show($id)
{
    /**
     * INCIDENT
     */
    $incident = DB::table('incidents')
        ->leftJoin(
            'incident_categories',
            'incidents.category_id',
            '=',
            'incident_categories.id'
        )
        ->leftJoin(
            'users as operators',
            'incidents.assigned_to',
            '=',
            'operators.id'
        )
        ->leftJoin(
            'users as creators',
            'incidents.reported_by',
            '=',
            'creators.id'
        )
        ->select(
            'incidents.*',
            'incident_categories.name as category_name',
            'operators.name as operator_name',
            'operators.email as operator_email',
            'creators.name as creator_name'
        )
        ->where('incidents.id', $id)
        ->first();

    if (!$incident) {
        abort(404);
    }

    /**
     * AUDIT LOGS
     */
    $logs = DB::table('audit_logs')
        ->leftJoin(
            'users',
            'audit_logs.user_id',
            '=',
            'users.id'
        )
        ->select(
            'audit_logs.*',
            'users.name as user_name'
        )
        ->where('table_name', 'incidents')
        ->where('record_id', $id)
        ->orderBy('audit_logs.created_at', 'desc')
        ->get();

    return view(
        'pages.opssight.incidents.show',
        [
            'incident' => $incident,
            'logs' => $logs,
        ]
    );
}

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $incident = DB::table('incidents')
            ->where('id', $id)
            ->first();

        if (!$incident) {
            abort(404);
        }

        $categories = DB::table('incident_categories')
            ->orderBy('name')
            ->get();

        $operators = DB::table('users')
            ->orderBy('name')
            ->get();

        return view(
            'pages.opssight.incidents.edit',
            [
                'incident' => $incident,
                'categories' => $categories,
                'operators' => $operators,
            ]
        );
    }
    
 public function update(Request $request, $id)
{
    /**
     * VALIDATION
     */
    $request->validate([
        'status' => 'required',
        'severity' => 'required',
        'assigned_to' => 'nullable',
        'notes' => 'nullable|string',
    ]);

    /**
     * GET INCIDENT
     */
    $incident = DB::table('incidents')
        ->where('id', $id)
        ->first();

    if (!$incident) {
        abort(404);
    }

    /**
     * OLD VALUES
     */
    $oldValues = [
        'status' => $incident->status,
        'severity' => $incident->severity,
        'assigned_to' => $incident->assigned_to,
    ];

    /**
     * NEW VALUES
     */
    $newValues = [
        'status' => $request->status,
        'severity' => $request->severity,
        'assigned_to' => $request->assigned_to,
    ];

    /**
     * CHECK CHANGES
     */
    $hasChanges = $oldValues != $newValues;

    /**
     * NO CHANGES
     */
    if (!$hasChanges && empty($request->notes)) {

        return redirect()
            ->route('incidents.show', $id)
            ->with(
                'info',
                'No changes detected.'
            );
    }

    /**
     * UPDATE INCIDENT
     */
    if ($hasChanges) {

        DB::table('incidents')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'severity' => $request->severity,
                'assigned_to' => $request->assigned_to,
                'updated_at' => now(),
            ]);
    }

    /**
     * CREATE AUDIT LOG
     */
    DB::table('audit_logs')->insert([

        'user_id' => auth()->id(),

        'action' => 'UPDATE_INCIDENT',

        'table_name' => 'incidents',

        'record_id' => $id,

        'old_values' => json_encode($oldValues),

        'new_values' => json_encode([
            ...$newValues,
            'notes' => $request->notes,
        ]),

        'ip_address' => $request->ip(),

        'created_at' => now(),
    ]);

    /**
     * REDIRECT
     */
    return redirect()
        ->route('incidents.show', $id)
        ->with(
            'success',
            'Incident updated successfully.'
        );
}

    /**
     * Delete incident.
     */
    public function destroy($id)
    {
        $incident = DB::table('incidents')
            ->where('id', $id)
            ->first();

        if (!$incident) {
            abort(404);
        }

        DB::table('incidents')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('incidents.index')
            ->with(
                'success',
                'Incident deleted successfully.'
            );
    }
}