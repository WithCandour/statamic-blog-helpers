<?php

namespace WithCandour\StatamicBlogHelpers\Tags;

use Carbon\Carbon;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Entry;
use Statamic\Tags\Tags;

class ArchiveTags extends Tags
{
    protected static $handle = 'blog-helpers-archive';

    public function dates()
    {
        $collection = $this->params->get('collection_name');

        if(!$collection) return $this->parse([]);

        $ctx = collect($this->context);

        $entries = Entry::whereCollection($collection);

        $publishedEntries = $entries->filter(function($entry) {
            return $entry->published();
        });

        $entryDates = $entries->multisort('date:desc')->map(function($entry) {
            if($entry->date()->lessThan(Carbon::now()->startOfYear())) {
                return [
                    'year' => $entry->date()->format('Y')
                ];
            }
            return [
                'year' => $entry->date()->format('Y'),
                'month' => $entry->date()->format('m')
            ];
        })->unique()->values();

        // If the selected year isn't in the list throw a 404
        $year = null;
        $month = $ctx->get('month');
        if($year = $ctx->get('year')) {
            if(!$entryDates->contains('year', $year)) {
                throw new NotFoundHttpException;
            }
        };

        $dates = $entryDates->map(function($date) use ($year, $month) {
            if(!empty($date['month'])) {
                $value = implode('-', [$date['month'], $date['year']]);
                $carbonDate = Carbon::parse("01-{$value}");
                return [
                    'selected' => ($year === $date['year'] && $month === $date['month']),
                    'value' => $carbonDate->format('Y/m'),
                    'nice' => $carbonDate->format('M Y')
                ];
            }

            return [
                'selected' => $year === $date['year'],
                'value' => $date['year'],
                'nice' => $date['year']
            ];
        });

        return $this->parseLoop($dates);
    }
}
