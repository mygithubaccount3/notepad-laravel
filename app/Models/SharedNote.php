<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\SharedNote
 *
 * @property int $id
 * @property int $user_id
 * @property int $note_id
 * @method static Builder|SharedNote newModelQuery()
 * @method static Builder|SharedNote newQuery()
 * @method static Builder|SharedNote query()
 * @method static Builder|SharedNote whereId($value)
 * @method static Builder|SharedNote whereNoteId($value)
 * @method static Builder|SharedNote whereUserId($value)
 * @mixin Eloquent
 * @property string $state
 * @method static Builder|SharedNote whereState($value)
 */
class SharedNote extends Eloquent
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'note_id'
    ];

    public $timestamps = false;
}
