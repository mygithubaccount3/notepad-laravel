<?php

namespace App\Http\Resources;

use App\Models\NoteTranslation;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NoteTranslationResource
 * @package App\Http\Resources
 * @property NoteTranslation $resource
 */
class NoteTranslationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param null $request
     * @return array
     */
    public function toArray($request = null)
    {
        return [
            'title' => $this->resource->title,
            'text' => $this->resource->text
        ];
    }
}
