<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait DataTableTrait
{
    /**
     * Apply data table filters, sorting, and pagination to a query
     *
     * @param Builder $query The base query to apply filters to
     * @param Request $request The request containing filter parameters
     * @param array $searchableColumns Columns that can be searched
     * @param array|null $sortableColumns Columns that can be sorted (defaults to searchable columns)
     * @param string $defaultSortField Default column to sort by
     * @param string $defaultSortDirection Default sort direction (asc or desc)
     * @return LengthAwarePaginator
     */
    protected function applyDataTableFilters(
        Builder $query,
        Request $request,
        array $searchableColumns,
        array $sortableColumns = null,
        string $defaultSortField = 'id',
        string $defaultSortDirection = 'desc'
    ): LengthAwarePaginator
    {
        // If sortable columns not specified, use searchable columns
        $sortableColumns = $sortableColumns ?? $searchableColumns;

        // Apply search filter if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', "%{$searchTerm}%");
                }
            });
        }

        // Apply sorting
        $sortField = $request->get('sort_field', $defaultSortField);
        $sortDirection = $request->get('sort_direction', $defaultSortDirection);

        // Ensure the sort field is allowed
        if (in_array($sortField, $sortableColumns)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy($defaultSortField, $defaultSortDirection);
        }

        // Apply pagination
        $perPage = $request->get('per_page', 10);
        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Format data for the frontend data table
     *
     * @param LengthAwarePaginator $paginator
     * @param callable $formatter Function to format each item
     * @return LengthAwarePaginator
     */
    protected function formatDataTableResults(LengthAwarePaginator $paginator, callable $formatter): LengthAwarePaginator
    {
        return $paginator->through($formatter);
    }
}
