<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanEntry extends Model
{
    protected $fillable = ['plan_month_id', 'user_id', 'day', 'status'];

    protected function casts(): array
    {
        return ['day' => 'integer'];
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(PlanMonth::class, 'plan_month_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
