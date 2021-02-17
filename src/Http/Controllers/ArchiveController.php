<?php

namespace WithCandour\StatamicBlogHelpers\Http\Controllers;

use App\Http\Controllers\Controller;
use Stringy\StaticStringy as Stringy;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\View\View;

class ArchiveController extends Controller
{
    /**
     * Show the archive view for the selected date
     *
     * @param string $year
     * @param string|null $month
     */
    public function archive(string $year, $month = null) {

        $conf = collect(config('statamic.blog-helpers'));
        $archives = collect($conf->get('archives'));

        $path = request()->path();
        $uri = explode('/archive/', $path)[0];

        $match = $archives->filter(function($archive) use ($uri) {
            return Stringy::ensureLeft($uri, '/') === Stringy::ensureLeft($archive['uri'], '/');
        })->first();

        if(!empty($match)) {
            $title_parts = [
                $match['title_prefix'],
                $year
            ];

            if($month) {
                $month_text = $this->indexToMonth($month);
                \array_splice($title_parts, 1, 0, $month_text);
            }

            return (new View)
                ->template($match['view'])
                ->layout($match['layout'])
                ->with([
                    'title' => implode(' ', $title_parts),
                    'year' => $year,
                    'month' => $month
                ]);
        }

        throw new NotFoundHttpException;
    }

    private function indexToMonth($month) {
        $dateString = "2021-{$month}-01";
        $timestamp = strtotime($dateString);
        return date('F', $timestamp);
    }
}
