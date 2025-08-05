<?php

namespace App\Actions;

use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImportLeads
{
    public function handle(): void
    {
        try {
            $path = storage_path('leads.json');

            if (!file_exists($path)) {
                throw new \Exception('The JSON file is not found');
            }

            $json = file_get_contents($path);
            $records = json_decode($json, true);
            DB::beginTransaction();

            $batch = [];
            foreach ($records as $record) {
                $batch[] = [
                    'merchant_name' => $record['merchant_name'],
                    'requested_amount' => $record['requested_amount'],
                    'lead_score' => $record['lead_score'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= 10000) { // if the array count exceeded on 10000 insert the data and reset the array to null
                    Lead::insert($batch);
                    $batch = [];
                }
            }

            if (!empty($batch)) { // fallback option if $batch is lessthan 10000
                Lead::insert($batch);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
            throw $th;
        }
    }
}
