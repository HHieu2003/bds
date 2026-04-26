<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToR2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-r2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all files from local public storage to Cloudflare R2';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration from local public storage to R2...');

        $localDisk = Storage::disk('public');
        $r2Disk = Storage::disk('r2');

        $directories = $localDisk->allDirectories();
        $files = $localDisk->allFiles();

        $totalFiles = count($files);
        $this->info("Found {$totalFiles} files to migrate.");

        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        $successCount = 0;
        $errorCount = 0;

        foreach ($files as $file) {
            // Bỏ qua các file ẩn như .gitignore
            if (str_ends_with($file, '.gitignore') || str_ends_with($file, '.DS_Store')) {
                $bar->advance();
                continue;
            }

            try {
                // Kiểm tra xem file đã tồn tại trên R2 chưa
                if (!$r2Disk->exists($file)) {
                    $stream = $localDisk->readStream($file);
                    $r2Disk->writeStream($file, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                }
                $successCount++;
            } catch (\Exception $e) {
                $this->error("\nFailed to upload {$file}: " . $e->getMessage());
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("Migration completed!");
        $this->info("Successfully uploaded: {$successCount}");
        if ($errorCount > 0) {
            $this->error("Failed to upload: {$errorCount}");
        }
    }
}
