<?php

namespace App\Console\Commands;

use App\Actions\ImportLeads;
use App\Jobs\AssignHighScoreLeads;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class FundDeals extends Command
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fund:deals';

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
        DB::disableQueryLog(); // Prevent memory bloat

        $this->info('Processing...');

        (new ImportLeads())->handle();

        $this->info('Import Completed..');

        DB::table('leads')->where("is_assigned", false)->where("lead_score", '>=', 80)->orderBy("id")->chunkById(1000, function ($leads) {
            AssignHighScoreLeads::dispatch($leads);
        });

        $this->info('Tha Lead Assign has been completed..');
    }
}
