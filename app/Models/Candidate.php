<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'regional_head', 'deputy_head'];

    public function getCandidateAttribute()
    {
        return ucwords("{$this->regional_head} - {$this->deputy_head}");
    }
}
