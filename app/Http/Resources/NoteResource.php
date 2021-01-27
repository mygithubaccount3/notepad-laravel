<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NoteResource
 * @property Note $resource
 * @package App\Http\Resources
 */
class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param null $request
     * @return array
     */
    public function toArray($request = null): array
    {
        return array_merge($this->resource->only([
            'id', 'uid', 'category', 'visibility', 'user_id'
        ]), [
            'translations' => NoteTranslationResource::collection($this->translates)
        ]);
    }
}
