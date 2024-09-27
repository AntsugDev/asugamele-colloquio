<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestConnect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-connect';

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
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $username = env('DB_USERNAME');
        $database = env('DB_DATABASE');

        // Attempt to connect
        try {
            DB::connection()->getPdo();
            $this->info("Successfully connected to the database '{$database}' at '{$host}:{$port}' as user '{$username}'.");
        } catch (\Exception $e) {
            $this->error('Failed to connect to the database: ' . $e->getMessage());
        }
    }
}
