<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Services\TagService;

class TagController extends Controller
{
    public function __construct(protected TagService $service) {}

    public function index()
    {
        return TagResource::collection(Tag::all());
    }

    public function store(StoreTagRequest $request)
    {
        $tag = $this->service->createTag($request->validated());

        return new TagResource($tag);
    }

    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $updated = $this->service->updateTag($tag, $request->validated());

        return new TagResource($updated);
    }

    public function destroy(Tag $tag)
    {
        $this->service->deleteTag($tag);

        return response()->noContent();
    }
}
