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
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PollingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view polling'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create polling'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit polling'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete polling'), only: ['destroy']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result polling'), only: ['result', 'graphic']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('verify polling'), only: ['verify']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('export polling'), only: ['export']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $pollings = Polling::with('polling_station')->latest()->get();
            return DataTables::of($pollings)
                ->addIndexColumn()
                ->addColumn('electoral_district', function ($row) {
                    return $row->polling_station->village->subdistrict->electoral_district ? $row->polling_station->village->subdistrict->electoral_district->name : '-';
                })
                ->addColumn('subdistrict', function ($row) {
                    return $row->polling_station->village->subdistrict ? $row->polling_station->village->subdistrict->name : '-';
                })
                ->addColumn('village', function ($row) {
                    return $row->polling_station->village ? $row->polling_station->village->name : '-';
                })
                ->addColumn('polling_station', function ($row) {
                    return $row->polling_station ? $row->polling_station->name : '-';
                })
                ->addColumn('type', function ($row) {
                    return $row->type();
                })
                ->addColumn('status', function ($row) {
                    return $row->status();
                })
                ->make(true);
        }

        return view('admin.polling.index');
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
            $user = Auth::user();
            $pollingstation = PollingStation::find($attr['polling_station_id']);

            if ($user->hasRole('Operator') && $pollingstation->id !== $user->polling_station_id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses'
                ], 403);
            }

            if ($request->hasFile('c1') && $request->file('c1')->isValid()) {
                $filename = $request->file('c1')->hashName();
                $request->file('c1')->storeAs('upload/c1/', $filename, 'public');
                $attr['c1'] = $filename;
            }

            $attr['candidate_votes'] = json_encode($attr['candidate_votes']);
            Polling::create($attr);

            return redirect()->back()->with('success', 'Data Berhasil Ditambah');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $th->getMessage()]);
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
        $candidates = Candidate::where('type', $polling->type)->count();
        return view('admin.polling.edit', compact('polling', 'candidates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollingRequest $request, Polling $polling)
    {
        try {
            $attr = $request->validated();

            if ($request->file('c1') && $request->file('c1')->isValid()) {

                $path = storage_path('app/public/upload/c1/');
                $filename = $request->file('c1')->hashName();

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $request->file('c1')->storeAs('upload/c1/', $filename, 'public');

                // delete c1 from storage
                if ($polling->c1 != null && file_exists($path . $polling->c1)) {
                    unlink($path . $polling->c1);
                }

                $attr['c1'] = $filename;
            }

            $attr['status'] = 0;

            $polling->update($attr);

            return redirect()
                ->back()
                ->with('success', __('Data berhasil diupdate.'));
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $th->getMessage()]);
        }
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
        $allowedPollingStation = Auth::user()->polling_station_id;

        if (auth()->user()->hasRole('Operator')) {
            $data['pollingstations'] = PollingStation::where("village_id", $request->village_id)
                ->where('id', $allowedPollingStation)
                ->get(["name", "id"]);
        } else {
            $data['pollingstations'] = PollingStation::where("village_id", $request->village_id)
                ->get(["name", "id"]);
        }

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
        $userPollingStation = Auth::user()->polling_station_id;

        if (auth()->user()->hasRole('Operator') && $polling_station_id != $userPollingStation) {
            return response()->json([
                'Anda tidak memiliki akses ke hasil pemilihan suara untuk TPS ini.'
            ], 403);
        }

        // Mengambil hasil pemilihan dan kandidat
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
        $userPollingStation = Auth::user()->polling_station_id;

        if (auth()->user()->hasRole('Operator') && $polling_station_id != $userPollingStation) {
            return response()->json([
                'Anda tidak memiliki akses ke hasil pemilihan suara untuk TPS ini.'
            ], 403);
        }

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
        $type = $request->type;

        if ($type) {
            return (new PollingExport($type))->download('laporan pilkada.xlsx');
        } else {
            return redirect()->back()->with('toast_error', 'Maaf, tidak bisa export data');
        }
    }
}
