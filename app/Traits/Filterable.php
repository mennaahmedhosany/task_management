<?php

namespace App\Traits;

use App\Http\Requests\TaskListingRequest;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function applyFilters(TaskListingRequest $request, Builder $query): Builder
    {
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has(['from_due', 'to_due'])) {
            $query->whereBetween('due_date', [$request->from_due, $request->to_due]);
        }

        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        return $query;
    }
}
