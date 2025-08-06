<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        "lead_id",
        "funded_amount"
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, "lead_id"); // relation to fetch lead details
    }
}
