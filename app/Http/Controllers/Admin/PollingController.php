<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PollingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePollingRequest;
use App\Http\Requests\UpdatePollingRequest;
use App\Models\Candidate;
use App\Models\ElectoralDistrict;
use App\Models\Polling;
use App\Models\PollingStation;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $electoralDistricts = ElectoralDistrict::all();

        return view('admin.polling.create', compact('electoralDistricts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollingRequest $request)
    {
        try {
            $attr = $request->validated();

            if ($request->hasFile('c1') && $request->file('c1')->isValid()) {
                $filename = $request->file('c1')->hashName();

                $request->file('c1')->storeAs('upload/c1/', $filename, 'public');

                $attr['c1'] = $filename;
            }

            $attr['candidate_votes'] = json_encode($attr['candidate_votes']);

            Polling::create($attr);

            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Polling $polling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Polling $polling)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollingRequest $request, Polling $polling)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Polling $polling)
    {
        //
    }

    public function graphic()
    {
        $electoralDistricts = ElectoralDistrict::all();
        return view('admin.polling.graphic', compact('electoralDistricts'));
    }

    public function result()
    {
        $electoralDistricts = ElectoralDistrict::all();

        return view('admin.polling.result', compact('electoralDistricts'));
    }

    public function fetchSubdistrict(Request $request)
    {
        $data['subdistricts'] = Subdistrict::where("electoral_district_id", $request->electoral_district_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function fetchVillage(Request $request)
    {
        $data['villages'] = Village::where("subdistrict_id", $request->subdistrict_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function fetchPollingStation(Request $request)
    {
        $data['pollingstations'] = PollingStation::where("village_id", $request->village_id)
            ->get(["name", "id"]);

        return response()->json($data);
    }

    public function fetchCandidate(Request $request)
    {
        $candidates = Candidate::where('type', $request->type)->count();
        return response()->json(['candidates' => $candidates]);
    }

    public function fetchPollingResult(Request $request)
    {
        $polling_station_id = $request->input('polling_station_id');
        $type = $request->input('type');

        $pollingResult = Polling::where('polling_station_id', $polling_station_id)
            ->where('type', $type)
            ->first();

        $candidates = Candidate::where('type', $type)->get();

        return response()->json([
            'pollingResult' => $pollingResult,
            'candidates' => $candidates,
        ]);
    }

    public function fetchPollingGraphic(Request $request)
    {
        $polling_station_id = $request->input('polling_station_id');
        $type = $request->input('type');

        $pollingResult = Polling::where('polling_station_id', $polling_station_id)
            ->where('type', $type)
            ->whereNot('status', 0)
            ->first();

        $candidates = Candidate::where('type', $type)->get();

        return response()->json([
            'pollingResult' => $pollingResult,
            'candidates' => $candidates,
        ]);
    }

    public function verify(Request $request)
    {
        $polling_station_id = $request->input('polling_station_id');
        $status = $request->input('status');

        $polling = Polling::where('polling_station_id', $polling_station_id)->first();

        $polling->update([
            'status' => $status
        ]);

        return response()->json(['message' => 'Polling berhasil diverifikasi']);
    }

    public function exportExcel(Request $request)
    {
        return (new PollingExport())->download('laporan pilkada.xlsx');
    }
}
