<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidentCategoryController extends Controller
{
    /**
     * Display a listing of categories with incident count.
     */
    public function index(Request $request)
    {
        $query = DB::table('incident_categories')
            ->leftJoin(
                'incidents',
                'incident_categories.id',
                '=',
                'incidents.category_id'
            )
            ->select(
                'incident_categories.*',
                DB::raw('COUNT(incidents.id) as total_incidents')
            )
            ->groupBy(
                'incident_categories.id',
                'incident_categories.name',
                'incident_categories.created_at',
                'incident_categories.updated_at'
            );

/**
 * SEARCH FILTER
 */
if ($request->filled('search')) {

    $search = trim($request->search);

    $query->where(
        'incident_categories.name',
        'ILIKE',
        '%' . $search . '%'
    );
}

        /**
         * GET CATEGORIES (paginated)
         */
        $categories = $query
            ->orderBy('incident_categories.name')
            ->paginate(10)
            ->withQueryString();

        return view(
            'pages.opssight.categories.index',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('pages.opssight.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        /**
         * VALIDATION
         */
        $request->validate([
            'name'        => 'required|string|max:100|unique:incident_categories,name',
        ]);

        /**
         * INSERT CATEGORY
         */
        $categoryId = DB::table('incident_categories')->insertGetId([
            'name'        => trim($request->name),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        /**
         * AUDIT LOG
         */
        DB::table('audit_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'CREATE_CATEGORY',
            'table_name' => 'incident_categories',
            'record_id'  => $categoryId,
            'old_values' => null,
            'new_values' => json_encode([
                'name'        => $request->name,
            ]),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit($id)
    {
        $category = DB::table('incident_categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        return view(
            'pages.opssight.categories.edit',
            ['category' => $category]
        );
    }

   /**
 * Update the specified category.
 */
public function update(Request $request, $id)
{
    $category = DB::table('incident_categories')
        ->where('id', $id)
        ->first();

    if (!$category) {
        abort(404);
    }

    /**
     * VALIDATION
     */
    $request->validate([
        'name' => 'required|string|max:100|unique:incident_categories,name,' . $id,
    ]);

    /**
     * OLD VALUES
     */
    $oldValues = [
        'name' => $category->name,
    ];

    /**
     * UPDATE CATEGORY
     */
    DB::table('incident_categories')
        ->where('id', $id)
        ->update([
            'name'       => trim($request->name),
            'updated_at' => now(),
        ]);

    /**
     * AUDIT LOG
     */
    DB::table('audit_logs')->insert([
        'user_id'    => auth()->id(),
        'action'     => 'UPDATE_CATEGORY',
        'table_name' => 'incident_categories',
        'record_id'  => $id,
        'old_values' => json_encode($oldValues),
        'new_values' => json_encode([
            'name' => $request->name,
        ]),

        'ip_address' => $request->ip(),
        'created_at' => now(),
    ]);

    return redirect()
        ->route('categories.index')
        ->with('success', 'Category updated successfully.');
}

    /**
     * Delete category — only if no incidents are using it.
     */
    public function destroy(Request $request, $id)
    {
        $category = DB::table('incident_categories')
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        /**
         * CHECK USAGE — cannot delete if incidents reference this category
         */
        $usageCount = DB::table('incidents')
            ->where('category_id', $id)
            ->count();

        if ($usageCount > 0) {
            return redirect()
                ->route('categories.index')
                ->with(
                    'error',
                    "Cannot delete \"{$category->name}\" — it is used by {$usageCount} incident(s)."
                );
        }

        /**
         * DELETE CATEGORY
         */
        DB::table('incident_categories')
            ->where('id', $id)
            ->delete();

        /**
         * AUDIT LOG
         */
        DB::table('audit_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => 'DELETE_CATEGORY',
            'table_name' => 'incident_categories',
            'record_id'  => $id,
            'old_values' => json_encode([
                'name'        => $category->name,
            ]),
            'new_values' => null,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
