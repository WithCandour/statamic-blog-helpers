<?php

namespace WithCandour\StatamicBlogHelpers\Tags;

use Statamic\Tags\Tags;

class PaginationTags extends Tags
{
    protected static $handle = 'blog-helpers-pagination';

    public function count()
    {
        $name = $this->params->get('name', 'Posts');
        $limit = $this->params->int('limit', 10);
        $page = $this->params->int('page', 1);
        $total = $this->params->int('total', 1);

        $offset = (($page - 1) * $limit) + 1;
        $page_end = $offset + $limit > $total ? $total : ($offset - 1) + $limit;

        $parts = [
            "$offset-$page_end",
            "of",
            $total,
            $name
        ];

        return implode(' ', $parts);
    }
}
