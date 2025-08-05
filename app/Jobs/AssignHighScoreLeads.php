<?php

namespace App\Jobs;

use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignHighScoreLeads implements ShouldQueue
{
    use Queueable;
    public $leads;

    /**
     * Create a new job instance.
     */
    public function __construct($leads)
    {
        $this->leads = $leads;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            DB::beginTransaction();
            $insert = [];
            $ids = [];
            foreach ($this->leads as $key => $lead) {
                $ids[] = $lead->id;
                $insert[] = [
                    "lead_id" => $lead->id,
                    "funded_amount" => $lead->requested_amount,
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }

            if (!empty($insert)) {
                Deal::insert($insert);
                Lead::whereIn("id", $ids)->update(["is_assigned" => true]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            throw $th;
        }
    }
}
