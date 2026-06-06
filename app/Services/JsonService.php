<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JsonService
{

    public function getUsers()
    {
        return Http::get('https://jsonplaceholder.typicode.com/posts')->json();
    }
}
