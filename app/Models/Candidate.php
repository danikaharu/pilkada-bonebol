<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'regional_head', 'deputy_head', 'type', 'candidate_pair', 'photo'];

    public function getCandidateAttribute()
    {
        return ucwords("{$this->regional_head} - {$this->deputy_head}");
    }

    public function type()
    {
        if ($this->type == 1) {
            return 'Gubernur';
        } else {
            return 'Kepala Daerah';
        }
    }
}
