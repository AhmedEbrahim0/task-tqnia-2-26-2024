<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class ForceDeletePostsEveryMonth extends Command
{
    /**
     * The name and signature ofa the console command.
     *
     * @var string
     */
    protected $signature = 'app:force-delete-posts-every-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts=Post::onlyTrashed()->get();
        foreach($posts as $post)
            $post->forceDelete();
    }
}
