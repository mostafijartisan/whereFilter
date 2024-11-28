<?php

namespace Mostafijartisan\WhereFilter\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filter
{

    /**
     * Apply filters to the query based on the request.
     *
     * @param Builder $query
     * @param array $request
     * @return void
     */
    public function scopeWhereFilter(Builder $query, array $request): Builder
    {

        // Return early if there are no filters or if the request is empty
        if (empty($this->whereFilters) || empty($request)) {
            return $query;
        }

        foreach ($this->whereFilters as $filter) {

            $value = $request[$filter['request']] ?? null;

            // Skip if the request value is not set or empty
            if (empty($value)) {
                continue;
            }

            $relation = $filter['relation'];

            if (!empty($relation)) {
                // If a relation is specified, use whereHas
                $query->whereHas($relation, function ($subQuery) use ($filter, $value) {
                    $this->applyFilter($subQuery, $filter, $value);
                });
            } else {
                // If no relation, apply the filter directly on the query
                $this->applyFilter($query, $filter, $value);
            }
        }

        return $query;
    }

    /**
     * Apply the appropriate filter to the query.
     *
     * @param Builder $query
     * @param array $filter
     * @param mixed $value
     * @return void
     */
    protected function applyFilter(Builder $query, array $filter, $value): void
    {
        switch ($filter['query']) {
            case 'where':
                $query->where($filter['column'], $value);
                break;

            case 'whereLike':
                $query->where($filter['column'], 'like', "%{$value}%");
                break;

            case 'whereIn':
                $query->whereIn($filter['column'], (array) $value);
                break;

            case 'whereBetween':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($filter['column'], $value);
                }
                break;
        }
    }
}