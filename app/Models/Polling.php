<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polling extends Model
{
    use HasFactory;

    protected $fillable = ['polling_station_id', 'type', 'candidate_votes', 'invalid_votes', 'c1', 'status'];

    public function polling_station()
    {
        return $this->belongsTo(PollingStation::class);
    }

    public function status()
    {
        if ($this->status == 1) {
            return 'SUDAH DIVERIFIKASI';
        } else {
            return 'BELUM DIVERIFIKASI';
        }
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
