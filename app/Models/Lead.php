<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        "merchant_name",
        "requested_amount",
        "lead_score",
        "is_assigned"
    ];
}
