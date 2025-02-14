<?php

namespace App\Console\Commands;

use App\Enums\Posts\PostType;
use App\Models\Post;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostScheduledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:post-scheduled-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all scheduled posts at the current date';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $currentDate = Carbon::now('Asia/Ho_Chi_Minh');
        DB::beginTransaction();
        try {
            Post::query()
                ->where([
                    ['scheduled_date', '<=', $currentDate],
                    'status' => PostType::SCHEDULED,
                ])->update([
                    'scheduled_date' => null,
                    'status' => PostType::APPROVED,
                ]);
            
            DB::commit();
            $this->info('Post scheduled command executed successfully');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Post scheduled command failed in $currentDate");
            Log::error($e->getMessage());
        }
    }
}
