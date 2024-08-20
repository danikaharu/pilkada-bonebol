<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreElectoralDistrictRequest;
use App\Http\Requests\UpdateElectoralDistrictRequest;
use App\Models\ElectoralDistrict;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ElectoralDistrictController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view electoral district'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create electoral district'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit electoral district'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete electoral district'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $electoralDistricts = ElectoralDistrict::latest()->get();
            return DataTables::of($electoralDistricts)
                ->addIndexColumn()
                ->addColumn('action', 'admin.electoral_district.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.electoral_district.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.electoral_district.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreElectoralDistrictRequest $request)
    {
        try {
            $attr = $request->validated();

            ElectoralDistrict::create($attr);

            return redirect()->route('admin.electoraldistrict.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.electoralistrict.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ElectoralDistrict $electoraldistrict)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ElectoralDistrict $electoraldistrict)
    {
        return view('admin.electoral_district.edit', compact('electoraldistrict'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElectoralDistrictRequest $request, ElectoralDistrict $electoraldistrict)
    {
        try {
            $attr = $request->validated();

            $electoraldistrict->update($attr);

            return redirect()
                ->route('admin.electoraldistrict.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.electoraldistrict.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ElectoralDistrict $electoraldistrict)
    {
        try {
            $electoraldistrict->delete();

            return redirect()
                ->route('admin.electoraldistrict.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.electoraldistrict.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
