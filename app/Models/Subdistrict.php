<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = ['electoral_district_id', 'name'];

    public function electoral_district()
    {
        return $this->belongsTo(ElectoralDistrict::class);
    }
}
