<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Blog
 *
 * @property int $id
 * @property int $writer_id
 * @property string $title
 * @property string $content
 * @property bool $premium
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogTag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $writer
 * @method static \Database\Factories\BlogFactory factory(...$parameters)
 * @method static Builder|Blog newModelQuery()
 * @method static Builder|Blog newQuery()
 * @method static Builder|Blog published()
 * @method static Builder|Blog query()
 * @method static Builder|Blog tag(string $tag)
 * @method static Builder|Blog unpublished()
 * @method static Builder|Blog whereContent($value)
 * @method static Builder|Blog whereCreatedAt($value)
 * @method static Builder|Blog whereId($value)
 * @method static Builder|Blog wherePremium($value)
 * @method static Builder|Blog wherePublishedAt($value)
 * @method static Builder|Blog whereTitle($value)
 * @method static Builder|Blog whereUpdatedAt($value)
 * @method static Builder|Blog whereWriterId($value)
 * @mixin \Eloquent
 */
class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'writer_id',
        'title',
        'content',
        'premium',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'premium' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Writer Relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    /**
     * Tags relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_assignments');
    }

    /**
     * Comments relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class);
    }

    /**
     * Local scope to use tag($tag) for queries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tag
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTag(Builder $query, string $tag): Builder
    {
        return $query->whereHas('tags', function (Builder $query) use ($tag) {
            $query->where('title', $tag);
        });
    }

    /**
     * Local scope to use published() for queries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->whereNotNull('published_at')
            ->where('published_at', '<', Carbon::now());
    }

    /**
     * Local scope to use unpublished() for queries.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished(Builder $query): Builder
    {
        return $query->whereNull('published_at');
    }

    /**
     * Check if blog is published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(Carbon::now());
    }
}
