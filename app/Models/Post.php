<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 'excerpt',
        'body', 'cover_image', 'type', 'status', 'is_pinned',
        'is_locked', 'views_count', 'votes_count', 'comments_count',
        'published_at',
    ];

    protected $casts = [
        'is_pinned'    => 'boolean',
        'is_locked'    => 'boolean',
        'published_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // ── Accessors / Helpers ───────────────────────────────────────────────────

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->body));
        return (int) ceil($wordCount / 200);
    }

    public function getRenderedBodyAttribute(): string
    {
        $body = $this->body;

        // Escape HTML to prevent XSS first
        $body = e($body);

        // Code blocks FIRST (before nl2br touches them): ```...```
        // Replace newlines inside code blocks with a placeholder to protect them
        $codeBlocks = [];
        $body = preg_replace_callback('/```([\s\S]*?)```/', function ($m) use (&$codeBlocks) {
            $placeholder = '__CODEBLOCK_' . count($codeBlocks) . '__';
            $codeBlocks[$placeholder] = '<pre><code>' . $m[1] . '</code></pre>';
            return $placeholder;
        }, $body);

        // YouTube embeds: [youtube:VIDEO_ID] — also protect from nl2br
        $ytBlocks = [];
        $body = preg_replace_callback('/\[youtube:([A-Za-z0-9_-]{11})\]/', function ($m) use (&$ytBlocks) {
            $placeholder = '__YTBLOCK_' . count($ytBlocks) . '__';
            $ytBlocks[$placeholder] = '<div class="yt-embed-wrapper"><iframe src="https://www.youtube.com/embed/' . $m[1] . '" frameborder="0" allowfullscreen loading="lazy"></iframe></div>';
            return $placeholder;
        }, $body);

        // Images: ![alt](url) — protect from nl2br
        $imgBlocks = [];
        $body = preg_replace_callback('/!\[([^\]]*)\]\((https?:\/\/[^\)]+)\)/', function ($m) use (&$imgBlocks) {
            $placeholder = '__IMGBLOCK_' . count($imgBlocks) . '__';
            $imgBlocks[$placeholder] = '<img src="' . $m[2] . '" alt="' . $m[1] . '" class="prose-img">';
            return $placeholder;
        }, $body);

        // Headings: ## and ### — protect as block
        $headingBlocks = [];
        $body = preg_replace_callback('/^(#{2,3}) (.+)$/m', function ($m) use (&$headingBlocks) {
            $tag = strlen($m[1]) === 2 ? 'h2' : 'h3';
            $placeholder = '__HEADINGBLOCK_' . count($headingBlocks) . '__';
            $headingBlocks[$placeholder] = "<{$tag}>{$m[2]}</{$tag}>";
            return $placeholder;
        }, $body);

        // Inline code: `code`
        $body = preg_replace('/`([^`]+)`/', '<code>$1</code>', $body);

        // Bold: **text**
        $body = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $body);

        // Italic: *text*
        $body = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $body);

        // Now apply nl2br to remaining plain text
        $body = nl2br($body);

        // Restore all block placeholders (no <br> wrapping them)
        foreach ($codeBlocks as $placeholder => $html) {
            $body = str_replace(
                [nl2br($placeholder), $placeholder],
                $html,
                $body
            );
        }
        foreach ($ytBlocks as $placeholder => $html) {
            $body = str_replace(
                [nl2br($placeholder), $placeholder],
                $html,
                $body
            );
        }
        foreach ($imgBlocks as $placeholder => $html) {
            $body = str_replace(
                [nl2br($placeholder), $placeholder],
                $html,
                $body
            );
        }
        foreach ($headingBlocks as $placeholder => $html) {
            $body = str_replace(
                [nl2br($placeholder), $placeholder],
                $html,
                $body
            );
        }

        return $body;
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'announcement'  => '#f59e0b',
            'documentation' => '#8b5cf6',
            'changelog'     => '#10b981',
            default         => '#22d3ee',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }

    public function isBookmarkedBy(?User $user): bool
    {
        if (! $user) return false;
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function userVote(?User $user): int
    {
        if (! $user) return 0;
        $vote = $this->votes()->where('user_id', $user->id)->first();
        return $vote ? $vote->value : 0;
    }

    // Auto-generate slug on save
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->status === 'published' && ! $post->published_at) {
                $post->published_at = now();
            }
        });
    }
}
