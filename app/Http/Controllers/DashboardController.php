<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $lead = Lead::selectRaw('
        COUNT(*) as total_leads,
        SUM(CASE WHEN is_assigned = 1 THEN 1 ELSE 0 END) as assigned_deals
    ')->first();

        $deal = Deal::selectRaw('SUM(funded_amount) as total_funded')->first();

        $totalLeads = (int)($lead->total_leads ?? 0);
        $assignedLeads = (int)($lead->assigned_deals ?? 0);
        $unAssignedLeads = $totalLeads - $assignedLeads;
        $totalFunded = (float)($deal->total_funded ?? 0);

        return response()->json([
            'status' => true,
            'data' => [
                'total_funded' => number_format($totalFunded, 2),
                'assigned_leads' => number_format($assignedLeads, 0),
                'total_leads' => number_format($totalLeads, 0),
                'unassigned_leads' => number_format($unAssignedLeads, 0),
            ]
        ]);
    }
}
