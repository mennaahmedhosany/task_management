<?php

namespace App\Traits;

use App\Http\Requests\TaskListingRequest;
use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function applySorting(TaskListingRequest $request, Builder $query): Builder
    {
        if ($request->has('priority')) {
            $query->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low')");
        }

        if ($request->has('due_date')) {
            $query->orderBy('due_date', 'asc');
        }

        if ($request->has('created_at')) {
            $query->orderBy('created_at', 'asc');
        }

        return $query;
    }
}
