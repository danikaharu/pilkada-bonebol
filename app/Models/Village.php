<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['subdistrict_id', 'name'];

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }
}
