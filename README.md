# Statamic Blog Helpers

![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge&link=https://statamic.com)

Designed to enhance your statamic blog, currently this addon adds:
- Routes for archivable content (content which has a `date`)
- Tags for generating a list of all possible archive dates in a collection
- A [query scope](https://statamic.dev/extending/query-scopes-and-filters) for filtering your collections on the archive routes.

## Installation

#### Install via composer:
```
composer require withcandour/statamic-blog-helpers
```
Then publish the publishables from the service provider:
```
php artisan vendor:publish --provider="WithCandour\StatamicBlogHelpers\ServiceProvider"
```

## Tags
This addon provides a tag for generating the archive dates for a specific Statamic collection. 

By default the dates generated will be as follows:
- For any date within the current year, each month for which there is an entry will get pulled through
- Entries that have a date before the start of the current year will be grouped by their year

This example will generate a select option for each archive date.
```
{{ blog-helpers-archive:dates collection_name="blog_posts" }}
    <option value="{{ value }}" {{ if selected }}selected{{ /if }}>{{ nice }}</option>
{{ /blog-helpers-archive:dates }}
```

## Archives and Routing
By default the archive routes follow the pattern: `/{uri}/archive/{year}/{?month}`

Once installed a `config/statamic/blog-helpers.php` file will be created. In this file you may register your archives, the addon will then generate the routes and handle the rendering of the pages.

In this example an archive will be created for the `/blog` page (e.g. `blog/archive/2021/01`). It will use the `blog.archive` template view and the `layout` layout view. The title for this page will also be "Posts from" followed by the archive date.

```php
'archives' => [
    [
        'uri' => '/blog',
        'view' => 'blog.archive',
        'layout' => 'layout',
        'title_prefix' => 'Posts from'
    ],
]
```

## Filtering
This addon adds the "blog_helpers_archive_scope" query scope which you can use to filter your collection, simply give it an `archive_year` and an `archive_month` and it will filter the entries returned by your collection tag automatically.

In this example `blog_posts` from March 2019 will get pulled through:
```
{{ collection:blog_posts query_scope="blog_helpers_archive_scope" archive_year="2019" archive_month="03" }}
    <!-- Content -->
{{ /collection:blog_posts }}
```

The `year` and `month` variables will get passed to the template that gets rendered for the archive pages so you could simply use:
```
{{ collection:blog_posts query_scope="blog_helpers_archive_scope" :archive_year="year" :archive_month="month" }}
    <!-- Content -->
{{ /collection:blog_posts }}
```
