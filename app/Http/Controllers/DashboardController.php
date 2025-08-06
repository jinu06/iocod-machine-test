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

    public function getDeals(Request $request)
    {
        $term = $request->input('term', '');
        $page = $request->input('page', 1);

        $limit = 40;
        $offset = ($page - 1) * $limit;

        $query = Deal::query()
            ->with("lead")
            ->when(
                $term,
                fn($q) =>
                $q->whereRelation('lead', 'merchant_name', 'like', "%$term%")
            )
            ->orderBy('id', 'desc');

        $results = $query->offset($offset)->limit($limit)->get();

        return response()->json([
            'results' => $results->map(fn($deal) => [
                'id' => $deal->lead_id,
                'text' => $deal->lead->merchant_name ?? 'Unknown',
            ]),
            'pagination' => ['more' => $results->count() === $limit], // checks about more data
        ]);
    }
}
