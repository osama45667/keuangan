<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LinkStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:link-fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the symbolic link for public storage with proper handling';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = public_path('storage');
        $targetPath = storage_path('app/public');

        // Ensure target directory exists
        if (!is_dir($targetPath)) {
            mkdir($targetPath, 0775, true);
        }

        // For Windows: Use Windows junction instead of symlink
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Remove existing junction if it exists
            if (is_dir($storagePath)) {
                exec("rmdir /s /q \"$storagePath\" 2>nul", $output, $result);
                if ($result === 0) {
                    $this->info('Removed existing storage junction');
                }
            }

            // Create the junction
            $cmd = "mklink /J \"$storagePath\" \"$targetPath\"";
            exec($cmd, $output, $result);
            
            if ($result === 0) {
                $this->info('✓ Storage junction created successfully!');
                $this->line("Storage: $storagePath -> $targetPath");
                return Command::SUCCESS;
            } else {
                $this->error('Failed to create junction. You may need to run this command as Administrator.');
                return Command::FAILURE;
            }
        } else {
            // For Linux/macOS: Use symlink
            if (is_link($storagePath)) {
                unlink($storagePath);
                $this->info('Removed existing symlink');
            } elseif (is_dir($storagePath)) {
                $backupPath = public_path('storage_backup');
                rename($storagePath, $backupPath);
                $this->info('Backed up existing storage directory');
            }

            symlink($targetPath, $storagePath);
            $this->info('✓ Storage symlink created successfully!');
            $this->line("Storage: $storagePath -> $targetPath");
            return Command::SUCCESS;
        }
    }
}
