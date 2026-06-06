<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ────────────────────────────────────────────────────────
        $admin = User::factory()->create([
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        // ── Regular users ──────────────────────────────────────────────────────
        $users = User::factory(10)->create();

        // ── Categories ────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Announcements',  'slug' => 'announcements',  'icon' => 'megaphone',  'color' => '#f59e0b', 'description' => 'Official product announcements and news.'],
            ['name' => 'Documentation',  'slug' => 'documentation',  'icon' => 'book-open',  'color' => '#8b5cf6', 'description' => 'Guides, tutorials, and API references.'],
            ['name' => 'Changelog',      'slug' => 'changelog',      'icon' => 'git-branch', 'color' => '#10b981', 'description' => 'Release notes and version history.'],
            ['name' => 'General',        'slug' => 'general',        'icon' => 'message-square', 'color' => '#22d3ee', 'description' => 'General community discussion.'],
            ['name' => 'Feature Requests','slug' => 'feature-requests','icon' => 'lightbulb', 'color' => '#f97316', 'description' => 'Suggest and vote on new features.'],
            ['name' => 'Bug Reports',    'slug' => 'bug-reports',    'icon' => 'bug',        'color' => '#ef4444', 'description' => 'Report issues and track fixes.'],
        ];

        foreach ($categories as $i => $cat) {
            Category::create(array_merge($cat, ['sort_order' => $i, 'is_active' => true]));
        }

        // ── Tags ──────────────────────────────────────────────────────────────
        $tagNames = ['API', 'v2.0', 'v3.0', 'Performance', 'Security', 'UI', 'CLI', 'Webhook', 'Integration', 'Hot-fix'];
        $tags = collect($tagNames)->map(fn ($name) => Tag::create([
            'name'  => $name,
            'slug'  => Str::slug($name),
            'color' => '#22d3ee',
        ]));

        // ── Posts ─────────────────────────────────────────────────────────────
        $postData = [
            ['title' => 'Welcome to the Community Hub', 'type' => 'announcement', 'category' => 1, 'is_pinned' => true,
             'body' => "We're thrilled to launch our new community platform. This is your space to ask questions, share discoveries, request features, and connect with the team.\n\nHere you'll find:\n- **Official announcements** and release notes\n- **Documentation** and tutorials\n- **Feature request** threads with community voting\n- **Bug tracking** and fix updates\n\nDive in, introduce yourself, and let's build something great together."],
            ['title' => 'v3.0 Release — What\'s New', 'type' => 'changelog', 'category' => 3,
             'body' => "## v3.0 — Major Release\n\nThis release brings a complete engine overhaul with significant performance improvements.\n\n### Breaking Changes\n- The legacy `v1` API endpoints have been removed\n- Authentication tokens now expire after 24h by default\n\n### New Features\n- Real-time webhook support\n- GraphQL API layer\n- Batch processing queue improvements (3x faster)\n\n### Bug Fixes\n- Fixed race condition in concurrent job processing\n- Resolved memory leak in long-running processes"],
            ['title' => 'Getting Started: Your First Integration', 'type' => 'documentation', 'category' => 2,
             'body' => "## Quick Start Guide\n\nThis guide gets you up and running in under 10 minutes.\n\n### Prerequisites\n- An active account\n- API key (Settings → API)\n\n### Step 1: Install the SDK\n\n```bash\nnpm install @ourplatform/sdk\n```\n\n### Step 2: Initialize\n\n```javascript\nimport { Client } from '@ourplatform/sdk';\n\nconst client = new Client({ apiKey: process.env.API_KEY });\n```\n\n### Step 3: Make your first call\n\n```javascript\nconst result = await client.process({ input: 'hello world' });\nconsole.log(result);\n```"],
            ['title' => 'Feature Request: Dark Mode for the Dashboard', 'type' => 'post', 'category' => 5,
             'body' => "I'd love to see a dark mode option in the main dashboard. Working late nights and the bright white interface is quite harsh.\n\nWould also love customizable accent colors to match our brand. Anyone else feel this way? Let's get some upvotes on this."],
            ['title' => 'Bug: Webhook timeout on large payloads', 'type' => 'post', 'category' => 6,
             'body' => "**Environment:** Production, v2.9.1\n**Reproducible:** Yes, consistently\n\n### Steps to Reproduce\n1. Create a webhook with payload > 2MB\n2. Trigger the event\n3. Observe: webhook times out after 5s with no retry\n\n### Expected\nWebhook should retry with exponential backoff\n\n### Actual\nSilently dropped. No error logged.\n\n**Workaround:** Splitting payload into chunks. Will update when resolved."],
        ];

        foreach ($postData as $data) {
            $post = Post::create([
                'user_id'      => $admin->id,
                'category_id'  => $data['category'],
                'title'        => $data['title'],
                'slug'         => Str::slug($data['title']),
                'body'         => $data['body'],
                'excerpt'      => Str::limit(strip_tags($data['body']), 160),
                'type'         => $data['type'],
                'status'       => 'published',
                'is_pinned'    => $data['is_pinned'] ?? false,
                'published_at' => now()->subDays(rand(1, 30)),
                'views_count'  => rand(50, 2000),
                'votes_count'  => rand(5, 150),
                'comments_count' => rand(0, 40),
            ]);

            $post->tags()->attach($tags->random(rand(1, 3))->pluck('id'));

            // Add some comments
            $commentCount = rand(2, 6);
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'body'    => fake()->paragraph(rand(1, 3)),
                    'votes_count' => rand(0, 20),
                ]);
            }
        }

        // Generate additional posts from random users
        $allCategories = Category::all();
        $types = ['post', 'documentation', 'changelog', 'announcement'];

        for ($i = 0; $i < 20; $i++) {
            $title = fake()->sentence(rand(5, 12));
            $post  = Post::create([
                'user_id'       => $users->random()->id,
                'category_id'   => $allCategories->random()->id,
                'title'         => $title,
                'slug'          => Str::slug($title) . '-' . Str::random(4),
                'body'          => implode("\n\n", fake()->paragraphs(rand(3, 8))),
                'excerpt'       => fake()->paragraph(2),
                'type'          => $types[array_rand($types)],
                'status'        => 'published',
                'is_pinned'     => false,
                'published_at'  => now()->subDays(rand(1, 60)),
                'views_count'   => rand(10, 500),
                'votes_count'   => rand(0, 80),
                'comments_count'=> rand(0, 20),
            ]);

            $post->tags()->attach($tags->random(rand(0, 3))->pluck('id'));
        }
    }
}
