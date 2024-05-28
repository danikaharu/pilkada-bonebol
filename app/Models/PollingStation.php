<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollingStation extends Model
{
    use HasFactory;

    protected $fillable = ['village_id', 'name', 'registered_voters'];

    public function village()
    {
        return $this->belongsTo(Village::class);
    }
}
