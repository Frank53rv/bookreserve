<?php

namespace App\Console\Commands;

use App\Services\BookCoverGenerator;
use Illuminate\Console\Command;

class GenerateBookCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:generate-covers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI covers for all books without one using OpenAI DALL-E';

    /**
     * Execute the console command.
     */
    public function handle(BookCoverGenerator $generator)
    {
        $this->info('Starting book cover generation...');

        $results = $generator->generateForAllBooks();

        $this->newLine();
        $this->info("✅ Success: {$results['success']}");
        $this->error("❌ Failed: {$results['failed']}");
        $this->comment("⏭️  Skipped: {$results['skipped']}");
        $this->newLine();

        $this->info('Cover generation complete!');

        return Command::SUCCESS;
    }
}
