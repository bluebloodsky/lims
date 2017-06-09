<?php

namespace Illuminate\Database\Query\Processors;

class MySqlProcessor extends Processor
{
    /**
     * Process the results of a column listing query.
     *
     * @param  array  $respults
     * @return array
     */
    public function processColumnListing($respults)
    {
        $mapping = function ($r) {
            $r = (object) $r;

            return $r->column_name;
        };

        return array_map($mapping, $respults);
    }
}
