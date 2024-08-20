<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectoralDistrict;
use App\Models\Polling;
use App\Models\PollingStation;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalElectoralDistrict = ElectoralDistrict::count();
        $totalSubdistrict = Subdistrict::count();
        $totalVillage = Village::count();
        $totalPollingStation = PollingStation::count();
        $totalPollingVerified = Polling::where('status', 1)->count();
        $totalPollingUnverified = Polling::where('status', 0)->count();

        return view('admin.dashboard.index', compact('totalElectoralDistrict', 'totalSubdistrict', 'totalVillage', 'totalPollingStation', 'totalPollingVerified', 'totalPollingUnverified'));
    }
}
