<?php

namespace App\Jobs;

use App\Helpers\ImageHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $thumbnailPath;
    public $categories;

    /**
     * Create a new job instance.
     */
    public function __construct($video, $thumbnailPath, $categories)
    {
        $this->video = $video;
        $this->thumbnailPath = $thumbnailPath;
        $this->categories = $categories;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Full path to file in storage
            $fullPath = storage_path('app/public/' . $this->thumbnailPath);

            // Use queue-safe helper
            $path = ImageHelper::optimizeAndStoreFromPath($fullPath, 'uploads/thumbnail');

            $this->video->thumbnail = $path;
            $this->video->save();

            $this->video->categories()->sync($this->categories);
        } catch (\Throwable $e) {
            Log::error('ProcessVideoUpload failed: '.$e->getMessage());
            throw $e;
        }
    }
}
