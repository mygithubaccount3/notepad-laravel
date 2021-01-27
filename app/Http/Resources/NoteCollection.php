<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class NoteCollection
 * @package App\Http\Resources
 */
class NoteCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request = null): array
    {
        return [
            'data' => NoteResource::collection($this->collection),
        ];
    }
}
