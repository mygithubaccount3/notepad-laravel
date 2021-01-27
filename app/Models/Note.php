<?php

namespace App\Models;

use App;
use App\Mail\NoteInvitation;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Astrotomic\Translatable\Translatable;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property string $uid
 * @property string $title
 * @property string $text
 * @property string $visibility
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @method static Builder|Note newModelQuery()
 * @method static Builder|Note newQuery()
 * @method static Builder|Note query()
 * @method static Builder|Note whereCreatedAt($value)
 * @method static Builder|Note whereId($value)
 * @method static Builder|Note whereText($value)
 * @method static Builder|Note whereTitle($value)
 * @method static Builder|Note whereUid($value)
 * @method static Builder|Note whereUpdatedAt($value)
 * @method static Builder|Note whereUserId($value)
 * @method static Builder|Note whereVisibility($value)
 * @mixin Eloquent
 * @property-read Collection|SharedNote[] $shared_notes
 * @property-read int|null $shared_notes_count
 * @property-read Collection|User[] $user_shared
 * @property-read int|null $user_shared_count
 */
class Note extends Eloquent
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['title', 'text'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'category',
        'visibility'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany */
    public function shared_notes(): HasMany
    {
        return $this->hasMany(SharedNote::class);
    }

    /** @return HasMany */
    public function translates(): HasMany
    {
        return $this->hasMany(NoteTranslation::class);
    }

    /**
     * @return BelongsToMany
     */
    public function user_shared(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'shared_notes');
    }

    /**
     * @param Request $request
     * @return Builder
     */
    public static function search(Request $request): Builder
    {
        $search = $request->input('search');
        $category = $request->input('category') ?? 'all';

        return self::where(static function (Builder $builder) use ($search, $category) {
            $builder->where(static function (Builder $builder) {
                $builder->where('user_id', Auth::id());
                $builder->orWhere('visibility', 'public');
            });

            $builder->whereHas('translates', static function (Builder $builder) use ($search, $category) {
                $builder->where(static function (Builder $builder) use ($search) {
                    $builder->where('title', 'LIKE', "%$search%");
                    $builder->orWhere('text', 'LIKE', "%$search%");
                });
            });

            if ($category !== 'all') {
                $builder->where('category', $category);
            }

        })->orderBy('updated_at', 'desc');
    }

    /**
     * @param $email
     */
    public function share($email): void
    {
        $user = User::whereEmail($email)->get();

        if ($user) {
            $this->user_shared()->attach($user);
        }

        Mail::to($email)->send(new NoteInvitation("https://notepad-goncharov-react.dev-rminds.nl/notes/{$this->uid}"));
    }
}
