<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PollingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\{StorePollingRequest, UpdatePollingRequest};
use App\Models\{Candidate, ElectoralDistrict, Polling, PollingStation, Subdistrict, Village};
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('verify polling'), only: ['verify']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('export polling'), only: ['export']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result all'), only: ['resultAll']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result electoral district'), only: ['resultElectoraldistrict']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result subdistrict'), only: ['resultSubdistrict']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result village'), only: ['resultVillage']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('result polling station'), only: ['result']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('graphic all'), only: ['graphicAll']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('graphic electoral district'), only: ['graphicElectoraldistrict']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('graphic subdistrict'), only: ['graphicSubdistrict']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('graphic village'), only: ['graphicVillage']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('graphic polling station'), only: ['graphic']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            // Ambil subdistrict_id dari user yang login
            $userSubdistrictId = Auth::user()->subdistrict_id;

            // Query dasar dengan relasi
            $query = Polling::with('polling_station.village.subdistrict.electoral_district')->latest();

            // Tambahkan filter jika user memiliki subdistrict_id
            if ($userSubdistrictId) {
                $query->whereHas('polling_station.village.subdistrict', function ($q) use ($userSubdistrictId) {
                    $q->where('id', $userSubdistrictId);
                });
            }

            return DataTables::of($query->get())
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
            DB::beginTransaction();
            $attr = $request->validated();
            $user = Auth::user();
            $pollingstation = PollingStation::find($attr['polling_station_id']);

            if ($user->hasRole('Operator') && $pollingstation->village->subdistrict->id !== $user->subdistrict_id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses'
                ], 403);
            }

            $c1 = [];
            if ($request->hasFile('c1')) {
                foreach ($request->file('c1') as $file) {
                    if ($file->isValid()) {
                        $filename = $file->hashName();
                        $file->storeAs('upload/c1/', $filename, 'public');
                        $c1[] = $filename;
                    }
                }
            }

            $attr['c1'] = json_encode($c1);
            $attr['candidate_votes'] = json_encode($attr['candidate_votes']);

            Polling::create($attr);

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil Ditambah');
        } catch (\Throwable $th) {
            DB::rollback();
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

            if ($request->hasFile('c1') && is_array($request->file('c1'))) {
                $path = storage_path('app/public/upload/c1/');

                // Inisialisasi array untuk menyimpan nama file gambar
                $filenames = [];

                // Cek jika ada file yang diupload
                foreach ($request->file('c1') as $file) {
                    if ($file->isValid()) { // Pastikan file valid
                        $filename = $file->hashName();

                        // Simpan file di folder yang sesuai
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true); // Membuat direktori jika belum ada
                        }

                        $file->storeAs('upload/c1/', $filename, 'public');
                        $filenames[] = $filename; // Menambahkan nama file ke array
                    }
                }

                // Hapus gambar lama jika ada
                if ($polling->c1 != null) {
                    $oldImages = json_decode($polling->c1); // Mengambil nama gambar lama
                    foreach ($oldImages as $image) {
                        $oldImagePath = $path . $image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); // Menghapus file lama
                        }
                    }
                }

                // Simpan nama file gambar dalam bentuk JSON di database
                $attr['c1'] = json_encode($filenames);
            }

            // Update status polling
            $attr['status'] = 0;

            // Update data polling
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
        return view('admin.polling.graphic.pollingstation', compact('electoralDistricts'));
    }

    public function result()
    {
        $electoralDistricts = ElectoralDistrict::all();

        return view('admin.polling.result.pollingstation', compact('electoralDistricts'));
    }

    public function fetchSubdistrict(Request $request)
    {
        $allowedSubdistrict = Auth::user()->subdistrict_id;

        if ($allowedSubdistrict) {
            $data['subdistricts'] = Subdistrict::where("electoral_district_id", $request->electoral_district_id)
                ->where('id', $allowedSubdistrict)
                ->get(["name", "id"]);
        } else {
            $data['subdistricts'] = Subdistrict::where("electoral_district_id", $request->electoral_district_id)
                ->get(["name", "id"]);
        }

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
        $subdistrict_id = $request->input('subdistrict_id');
        $polling_station_id = $request->input('polling_station_id');
        $type = $request->input('type');
        $userSubdistrict = Auth::user()->subdistrict_id;

        if (Auth::user()->hasRole('Operator') && $subdistrict_id != $userSubdistrict) {
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
        $subdistrict_id = $request->input('subdistrict_id');
        $polling_station_id = $request->input('polling_station_id');
        $type = $request->input('type');
        $userSubdistrict = Auth::user()->subdistrict_id;

        if (Auth::user()->hasRole('Operator') && $subdistrict_id != $userSubdistrict) {
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

    public function resultAll(Request $request)
    {
        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::where('type', $type)->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        // Ambil kandidat berdasarkan tipe jika ada
        $candidates = Candidate::where('type', $type)->get();

        // Membuat array untuk menyimpan total suara per kandidat
        $totalVotes = [];
        $totalInvalidVotes = 0;

        // Loop melalui polling untuk menjumlahkan suara
        foreach ($pollings as $polling) {
            // Decode data candidate_votes menjadi array
            $candidateVotes = json_decode($polling->candidate_votes, true);

            // Pastikan data candidate_votes adalah array
            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    // Menambahkan suara ke kandidat berdasarkan indeks
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }

            // Tambahkan suara tidak sah
            $totalInvalidVotes += (int) $polling->invalid_votes;  // Pastikan invalid_votes dihitung sebagai integer
        }

        // Menyusun data untuk view
        $data = [
            'candidates' => [],
            'invalid_votes' => number_format($totalInvalidVotes, 0, ',', '.'),  // Format dengan tanda koma
        ];

        // Loop melalui kandidat untuk menambahkan total suara
        foreach ($candidates as $index => $candidate) {
            // Pastikan suara kandidat dijumlahkan berdasarkan ID yang benar
            $candidateVotes = $totalVotes[$index] ?? 0;
            $data['candidates'][] = [
                'candidate_no' => $candidate->number, // Nomor urut kandidat
                'candidate_name' => $candidate->getCandidateAttribute(), // Nama kandidat
                'votes' => number_format($candidateVotes, 0, ',', '.'), // Total suara
            ];
        }

        return view('admin.polling.result.all', compact('data', 'type'));
    }

    public function resultVillage(Request $request)
    {
        $allowedSubdistrict = Auth::user()->subdistrict_id;

        if ($allowedSubdistrict) {
            $villages = Village::whereHas('subdistrict', function ($query) use ($allowedSubdistrict) {
                $query->where('id', $allowedSubdistrict);
            })->get();
        } else {
            $villages = Village::latest()->get(["name", "id"]);
        }

        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $villagesId = $request->input('village_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station', function ($query) use ($villagesId) {
                $query->where('village_id', $villagesId);
            })->where('type', $type)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        // Ambil kandidat berdasarkan tipe jika ada
        $candidates = Candidate::where('type', $type)->get();

        // Membuat array untuk menyimpan total suara per kandidat
        $totalVotes = [];
        $totalInvalidVotes = 0;

        // Loop melalui polling untuk menjumlahkan suara
        foreach ($pollings as $polling) {
            // Decode data candidate_votes menjadi array
            $candidateVotes = json_decode($polling->candidate_votes, true);

            // Pastikan data candidate_votes adalah array
            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    // Menambahkan suara ke kandidat berdasarkan indeks
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }

            // Tambahkan suara tidak sah
            $totalInvalidVotes += (int) $polling->invalid_votes;  // Pastikan invalid_votes dihitung sebagai integer
        }

        // Menyusun data untuk view
        $data = [
            'candidates' => [],
            'invalid_votes' => number_format($totalInvalidVotes, 0, ',', '.'),  // Format dengan tanda koma
        ];

        // Loop melalui kandidat untuk menambahkan total suara
        foreach ($candidates as $index => $candidate) {
            // Pastikan suara kandidat dijumlahkan berdasarkan ID yang benar
            $candidateVotes = $totalVotes[$index] ?? 0;
            $data['candidates'][] = [
                'candidate_no' => $candidate->number, // Nomor urut kandidat
                'candidate_name' => $candidate->getCandidateAttribute(), // Nama kandidat
                'votes' => number_format($candidateVotes, 0, ',', '.'), // Total suara
            ];
        }

        return view('admin.polling.result.village', compact('data', 'type', 'villages'));
    }

    public function resultSubdistrict(Request $request)
    {
        $allowedSubdistrict = Auth::user()->subdistrict_id;

        if ($allowedSubdistrict) {
            $subdistricts = Subdistrict::where('id', $allowedSubdistrict)->get(["name", "id"]);
        } else {
            $subdistricts = Subdistrict::latest()->get(["name", "id"]);
        }

        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $subdistrictsId = $request->input('subdistrict_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station.village.subdistrict', function ($query) use ($subdistrictsId) {
                $query->where('id', $subdistrictsId); // ID kecamatan
            })->where('type', $type)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        // Ambil kandidat berdasarkan tipe jika ada
        $candidates = Candidate::where('type', $type)->get();

        // Membuat array untuk menyimpan total suara per kandidat
        $totalVotes = [];
        $totalInvalidVotes = 0;

        // Loop melalui polling untuk menjumlahkan suara
        foreach ($pollings as $polling) {
            // Decode data candidate_votes menjadi array
            $candidateVotes = json_decode($polling->candidate_votes, true);

            // Pastikan data candidate_votes adalah array
            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    // Menambahkan suara ke kandidat berdasarkan indeks
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }

            // Tambahkan suara tidak sah
            $totalInvalidVotes += (int) $polling->invalid_votes;  // Pastikan invalid_votes dihitung sebagai integer
        }

        // Menyusun data untuk view
        $data = [
            'candidates' => [],
            'invalid_votes' => number_format($totalInvalidVotes, 0, ',', '.'),  // Format dengan tanda koma
        ];

        // Loop melalui kandidat untuk menambahkan total suara
        foreach ($candidates as $index => $candidate) {
            // Pastikan suara kandidat dijumlahkan berdasarkan ID yang benar
            $candidateVotes = $totalVotes[$index] ?? 0;
            $data['candidates'][] = [
                'candidate_no' => $candidate->number, // Nomor urut kandidat
                'candidate_name' => $candidate->getCandidateAttribute(), // Nama kandidat
                'votes' => number_format($candidateVotes, 0, ',', '.'), // Total suara
            ];
        }

        return view('admin.polling.result.subdistrict', compact('data', 'type', 'subdistricts'));
    }

    public function resultElectoraldistrict(Request $request)
    {
        $electoraldistricts = ElectoralDistrict::all();
        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $electoraldistrictsId = $request->input('electoraldistrict_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station.village.subdistrict.electoral_district', function ($query) use ($electoraldistrictsId) {
                $query->where('id', $electoraldistrictsId); // ID kecamatan
            })->where('type', $type)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        // Ambil kandidat berdasarkan tipe jika ada
        $candidates = Candidate::where('type', $type)->get();

        // Membuat array untuk menyimpan total suara per kandidat
        $totalVotes = [];
        $totalInvalidVotes = 0;

        // Loop melalui polling untuk menjumlahkan suara
        foreach ($pollings as $polling) {
            // Decode data candidate_votes menjadi array
            $candidateVotes = json_decode($polling->candidate_votes, true);

            // Pastikan data candidate_votes adalah array
            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    // Menambahkan suara ke kandidat berdasarkan indeks
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }

            // Tambahkan suara tidak sah
            $totalInvalidVotes += (int) $polling->invalid_votes;  // Pastikan invalid_votes dihitung sebagai integer
        }

        // Menyusun data untuk view
        $data = [
            'candidates' => [],
            'invalid_votes' => number_format($totalInvalidVotes, 0, ',', '.'),  // Format dengan tanda koma
        ];

        // Loop melalui kandidat untuk menambahkan total suara
        foreach ($candidates as $index => $candidate) {
            // Pastikan suara kandidat dijumlahkan berdasarkan ID yang benar
            $candidateVotes = $totalVotes[$index] ?? 0;
            $data['candidates'][] = [
                'candidate_no' => $candidate->number, // Nomor urut kandidat
                'candidate_name' => $candidate->getCandidateAttribute(), // Nama kandidat
                'votes' => number_format($candidateVotes, 0, ',', '.'), // Total suara
            ];
        }

        return view('admin.polling.result.electoraldistrict', compact('data', 'type', 'electoraldistricts'));
    }

    public function graphicAll(Request $request)
    {
        // Default tipe pemilihan ke 1 jika tidak ada input
        $type = $request->input('type', 1);

        // Ambil polling berdasarkan tipe pemilihan dan status aktif
        $pollings = Polling::where('type', $type)->where('status', 1)->get();

        $totalVotes = [];

        // Looping polling untuk menjumlahkan suara per kandidat
        foreach ($pollings as $polling) {
            $candidateVotes = json_decode($polling->candidate_votes, true);

            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }
        }

        // Ambil data kandidat berdasarkan tipe pemilihan
        $candidates = Candidate::where('type', $type)->orderBy('number')->get();

        // Format data kandidat dan total suara
        $candidateNames = $candidates->pluck('candidate')->toArray();
        $candidateNumbers = $candidates->pluck('number')->toArray();

        $totalVotesFormatted = [];
        foreach ($candidates as $index => $candidate) {
            $totalVotesFormatted[] = $totalVotes[$index] ?? 0;
        }

        if ($request->ajax()) {
            return response()->json([
                'candidateNames' => $candidateNames,
                'totalVotes' => $totalVotesFormatted,
            ]);
        }

        // Kirim data ke tampilan
        return view('admin.polling.graphic.all', compact('totalVotesFormatted', 'candidateNames', 'type'));
    }

    public function graphicElectoralDistrict(Request $request)
    {
        $electoraldistricts = ElectoralDistrict::all();

        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $electoraldistrictsId = $request->input('electoraldistrict_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station.village.subdistrict.electoral_district', function ($query) use ($electoraldistrictsId) {
                $query->where('id', $electoraldistrictsId); // ID daerah pemilihan
            })->where('type', $type)
                ->where('status', 1)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        $totalVotes = [];

        // Looping polling untuk menjumlahkan suara per kandidat
        foreach ($pollings as $polling) {
            $candidateVotes = json_decode($polling->candidate_votes, true);

            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }
        }

        // Ambil data kandidat berdasarkan tipe pemilihan
        $candidates = Candidate::where('type', $type)->orderBy('number')->get();

        // Format data kandidat dan total suara
        $candidateNames = $candidates->pluck('candidate')->toArray();
        $candidateNumbers = $candidates->pluck('number')->toArray();

        $totalVotesFormatted = [];
        foreach ($candidates as $index => $candidate) {
            $totalVotesFormatted[] = $totalVotes[$index] ?? 0;
        }

        if ($request->ajax()) {
            return response()->json([
                'candidateNames' => $candidateNames,
                'totalVotes' => $totalVotesFormatted,
            ]);
        }

        return view('admin.polling.graphic.electoraldistrict', compact('totalVotesFormatted', 'candidateNames', 'type', 'electoraldistricts'));
    }

    public function graphicSubdistrict(Request $request)
    {
        $allowedSubdistrict = Auth::user()->subdistrict_id;

        if ($allowedSubdistrict) {
            $subdistricts = Subdistrict::where('id', $allowedSubdistrict)->get(["name", "id"]);
        } else {
            $subdistricts = Subdistrict::latest()->get(["name", "id"]);
        }

        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $subdistrictId = $request->input('subdistrict_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station.village.subdistrict', function ($query) use ($subdistrictId) {
                $query->where('id', $subdistrictId); // ID daerah pemilihan
            })->where('type', $type)
                ->where('status', 1)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        $totalVotes = [];

        // Looping polling untuk menjumlahkan suara per kandidat
        foreach ($pollings as $polling) {
            $candidateVotes = json_decode($polling->candidate_votes, true);

            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }
        }

        // Ambil data kandidat berdasarkan tipe pemilihan
        $candidates = Candidate::where('type', $type)->orderBy('number')->get();

        // Format data kandidat dan total suara
        $candidateNames = $candidates->pluck('candidate')->toArray();
        $candidateNumbers = $candidates->pluck('number')->toArray();

        $totalVotesFormatted = [];
        foreach ($candidates as $index => $candidate) {
            $totalVotesFormatted[] = $totalVotes[$index] ?? 0;
        }

        if ($request->ajax()) {
            return response()->json([
                'candidateNames' => $candidateNames,
                'totalVotes' => $totalVotesFormatted,
            ]);
        }

        return view('admin.polling.graphic.subdistrict', compact('totalVotesFormatted', 'candidateNames', 'type', 'subdistricts'));
    }

    public function graphicVillage(Request $request)
    {
        $allowedSubdistrict = Auth::user()->subdistrict_id;

        if ($allowedSubdistrict) {
            $villages = Village::whereHas('subdistrict', function ($query) use ($allowedSubdistrict) {
                $query->where('id', $allowedSubdistrict);
            })->get();
        } else {
            $villages = Village::latest()->get(["name", "id"]);
        }

        // Ambil parameter tipe pemilihan
        $type = $request->input('type', null);
        $villageId = $request->input('village_id', null);

        // Ambil polling berdasarkan tipe yang dipilih (gubernur atau kepala daerah)
        if ($type) {
            // Ambil polling yang sesuai dengan type
            $pollings = Polling::whereHas('polling_station.village', function ($query) use ($villageId) {
                $query->where('id', $villageId); // ID daerah pemilihan
            })->where('type', $type)
                ->where('status', 1)
                ->get();
        } else {
            // Ambil semua polling jika type tidak dipilih
            $pollings = Polling::all();
        }

        $totalVotes = [];

        // Looping polling untuk menjumlahkan suara per kandidat
        foreach ($pollings as $polling) {
            $candidateVotes = json_decode($polling->candidate_votes, true);

            if (is_array($candidateVotes)) {
                foreach ($candidateVotes as $index => $votes) {
                    $totalVotes[$index] = ($totalVotes[$index] ?? 0) + $votes;
                }
            }
        }

        // Ambil data kandidat berdasarkan tipe pemilihan
        $candidates = Candidate::where('type', $type)->orderBy('number')->get();

        // Format data kandidat dan total suara
        $candidateNames = $candidates->pluck('candidate')->toArray();
        $candidateNumbers = $candidates->pluck('number')->toArray();

        $totalVotesFormatted = [];
        foreach ($candidates as $index => $candidate) {
            $totalVotesFormatted[] = $totalVotes[$index] ?? 0;
        }

        if ($request->ajax()) {
            return response()->json([
                'candidateNames' => $candidateNames,
                'totalVotes' => $totalVotesFormatted,
            ]);
        }

        return view('admin.polling.graphic.village', compact('totalVotesFormatted', 'candidateNames', 'type', 'villages'));
    }
}
