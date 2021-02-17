<?php

namespace WithCandour\StatamicBlogHelpers;

use Statamic\Providers\AddonServiceProvider;
use WithCandour\StatamicBlogHelpers\Tags\ArchiveTags;
use WithCandour\StatamicBlogHelpers\Scopes\BlogHelpersArchiveScope;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    protected $scopes = [
        BlogHelpersArchiveScope::class
    ];

    protected $tags = [
        ArchiveTags::class
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        parent::boot();

        $this->mergeConfigFrom(__DIR__ . '/../config/statamic/blog-helpers.php', 'statamic.blog-helpers');
        $this->publishes([
            __DIR__ . '/../config/statamic/blog-helpers.php' => config_path('statamic/blog-helpers.php'),
        ], 'config');
    }
}
