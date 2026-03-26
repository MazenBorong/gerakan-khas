<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['on_date', 'label'];

    protected function casts(): array
    {
        return ['on_date' => 'date'];
    }
}
