<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanMonth extends Model
{
    protected $fillable = ['year', 'month'];

    protected function casts(): array
    {
        return ['year' => 'integer', 'month' => 'integer'];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(PlanEntry::class, 'plan_month_id');
    }
}
