<?php

namespace App\Exceptions;

use Exception;

class ArticleNotFoundException extends Exception
{
    public function __construct(protected string $slug)
    {
        parent::__construct("Article with slug '{$this->slug}' was not found.", 404);
    }
}
