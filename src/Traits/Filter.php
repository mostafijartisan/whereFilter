<?php

namespace Mostafijartisan\WhereFilter\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filter
{
    /**
     * Apply filters to the query based on the request.
     */
    public function scopeWhereFilter(Builder $query, array $request): Builder
    {

        if(empty($this->filters)){
            return $query;
        }

        foreach ($this->filters as $filter) {
            $column = $filter['column'];
            $queryType = $filter['queryType'] ?? 'where';

            if (isset($request[$column]) && !empty($request[$column])) {
                switch ($queryType) {
                    case 'where':
                        $query->where(column: $column, operator: $request[$column]);
                        break;
                    case 'whereLike':
                        $query->where(column: $column, operator: 'like', value: '%' . $request[$column] . '%');
                        break;
                    case 'whereIn':
                        $query->whereIn(column: $column, values: (array) $request[$column]);
                        break;
                    case 'whereBetween':
                        $query->whereBetween(column: $column, values: $request[$column]);
                        break;
                }
            }
        }

        return $query;
    }
}
