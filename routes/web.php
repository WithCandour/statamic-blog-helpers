<?php

$config = collect(config('statamic.blog-helpers'));
$archives = collect($config->get('archives', []));

Route::namespace("\WithCandour\StatamicBlogHelpers\Http\Controllers")
    ->group(function() use ($archives) {
        $archives->each(function($archive) {
            Route::get("{$archive['uri']}/archive/{year}", 'ArchiveController@archive');
            Route::get("{$archive['uri']}/archive/{year}/{month}", 'ArchiveController@archive');
        });
    });
