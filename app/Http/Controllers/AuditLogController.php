<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Display a paginated, filterable audit log listing.
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $query = DB::table('audit_logs')
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.id')
            ->select(
                'audit_logs.*',
                'users.name as user_name',
                'users.email as user_email'
            );

        /**
         * SEARCH — matches user name, action, table_name, or record_id
         */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%'.$search.'%')
                    ->orWhere('audit_logs.action', 'like', '%'.$search.'%')
                    ->orWhere('audit_logs.table_name', 'like', '%'.$search.'%')
                    ->orWhere('audit_logs.record_id', $search);
            });
        }

        /**
         * FILTER — Action
         */
        if ($request->filled('action')) {
            $query->where('audit_logs.action', $request->action);
        }

        /**
         * FILTER — Table (Target)
         */
        if ($request->filled('table')) {
            $query->where('audit_logs.table_name', $request->table);
        }

        /**
         * FILTER — User
         */
        if ($request->filled('user')) {
            $query->where('audit_logs.user_id', $request->user);
        }

        /**
         * FILTER — Date (from/to)
         */
        if ($request->filled('date_from')) {
            $query->whereDate('audit_logs.created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('audit_logs.created_at', '<=', $request->date_to);
        }

        /**
         * SORT — latest first
         */
        $logs = $query
            ->orderBy('audit_logs.created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        /**
         * DROPDOWN DATA for filters
         */
        $actions = DB::table('audit_logs')
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $tables = DB::table('audit_logs')
            ->select('table_name')
            ->distinct()
            ->orderBy('table_name')
            ->pluck('table_name');

        $users = DB::table('users')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('pages.opssight.audit-logs.index', [
            'logs' => $logs,
            'actions' => $actions,
            'tables' => $tables,
            'users' => $users,
        ]);
    }

    /**
     * Get JSON details of an audit log entry's target resource.
     */
    public function details($id)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $log = DB::table('audit_logs')
            ->where('id', $id)
            ->first();

        if (! $log) {
            return response()->json([
                'error' => 'Log not found',
            ], 404);
        }

        $exists = false;
        $data = null;

        /**
         * INCIDENT DETAILS
         */
        if ($log->table_name === 'incidents') {

            $data = DB::table('incidents')
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
                ->where('incidents.id', $log->record_id)
                ->first();

            if ($data) {

                $exists = true;

            } else {

                /**
                 * FALLBACK FROM AUDIT LOG
                 */
                $newVals = $log->new_values
                    ? json_decode($log->new_values, true)
                    : [];

                $oldVals = $log->old_values
                    ? json_decode($log->old_values, true)
                    : [];

                $data = [

                    'id' => $log->record_id,

                    'title' => $newVals['title']
                        ?? $oldVals['title']
                        ?? 'Deleted Incident #'.$log->record_id,

                    'description' => $newVals['description']
                        ?? $oldVals['description']
                        ?? 'No description stored in audit log.',

                    'severity' => $newVals['severity']
                        ?? $oldVals['severity']
                        ?? 'N/A',

                    'status' => $newVals['status']
                        ?? $oldVals['status']
                        ?? 'N/A',

                    'created_at' => $newVals['created_at']
                        ?? $oldVals['created_at']
                        ?? null,

                    'updated_at' => $newVals['updated_at']
                        ?? $oldVals['updated_at']
                        ?? null,

                    'incident_date' => $newVals['incident_date']
                        ?? $oldVals['incident_date']
                        ?? null,

                    'category_name' => $newVals['category_name']
                        ?? $oldVals['category_name']
                        ?? 'Uncategorized',

                    'operator_name' => null,

                    'creator_name' => null,

                    'is_deleted' => true,

                    'action' => $log->action,

                    'old_values' => $oldVals,

                    'new_values' => $newVals,
                ];
            }

            /**
             * CATEGORY DETAILS
             */
        } elseif ($log->table_name === 'incident_categories') {

            $data = DB::table('incident_categories')
                ->where('id', $log->record_id)
                ->first();

            if ($data) {

                $exists = true;

            } else {

                /**
                 * FALLBACK FROM AUDIT LOG
                 */
                $newVals = $log->new_values
                    ? json_decode($log->new_values, true)
                    : [];

                $oldVals = $log->old_values
                    ? json_decode($log->old_values, true)
                    : [];

                $data = [

                    'id' => $log->record_id,

                    'name' => $newVals['name']
                        ?? $oldVals['name']
                        ?? 'Deleted Category #'.$log->record_id,

                    'created_at' => $newVals['created_at']
                        ?? $oldVals['created_at']
                        ?? null,

                    'updated_at' => $newVals['updated_at']
                        ?? $oldVals['updated_at']
                        ?? null,

                    'is_deleted' => true,

                    'action' => $log->action,

                    'old_values' => $oldVals,

                    'new_values' => $newVals,
                ];
            }

            /**
             * USER DETAILS
             */
        } elseif ($log->table_name === 'users') {

            $data = DB::table('users')
                ->where('id', $log->record_id)
                ->first();

            if ($data) {

                $exists = true;

            } else {

                /**
                 * FALLBACK FROM AUDIT LOG
                 */
                $newVals = $log->new_values
                    ? json_decode($log->new_values, true)
                    : [];

                $oldVals = $log->old_values
                    ? json_decode($log->old_values, true)
                    : [];

                $data = [

                    'id' => $log->record_id,

                    'name' => $newVals['name']
                        ?? $oldVals['name']
                        ?? 'Deleted User #'.$log->record_id,

                    'email' => $newVals['email']
                        ?? $oldVals['email']
                        ?? 'N/A',

                    'role' => $newVals['role']
                        ?? $oldVals['role']
                        ?? 'N/A',

                    'created_at' => $newVals['created_at']
                        ?? $oldVals['created_at']
                        ?? null,

                    'updated_at' => $newVals['updated_at']
                        ?? $oldVals['updated_at']
                        ?? null,

                    'is_deleted' => true,

                    'action' => $log->action,

                    'old_values' => $oldVals,

                    'new_values' => $newVals,
                ];
            }
        }

        return response()->json([
            'table_name' => $log->table_name,
            'record_id' => $log->record_id,
            'exists' => $exists,
            'data' => $data,
        ]);
    }
}
