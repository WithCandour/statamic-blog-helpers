<?php

namespace WithCandour\StatamicBlogHelpers\Scopes;

use Statamic\Query\Scopes\Scope;
use Carbon\Carbon;

class BlogHelpersArchiveScope extends Scope
{
    /**
     * @inheritdoc
     */
    public function apply($query, $values)
    {
        $year = $values->get('archive_year');
        $month = $values->get('archive_month');
        if (empty($month) || empty($year)) {
            return '';
        }
        
        if (!empty($month)) {
            $since = Carbon::parse("01-{$month}-{$year}");
            $until = (new Carbon($since))->endOfMonth();
        } else {
            $since = Carbon::parse("01-01-{$year}");
            $until = (new Carbon($since))->endOfYear();
        }

        $query->where('date', '>', $since);
        $query->where('date', '<', $until);
    }
}
