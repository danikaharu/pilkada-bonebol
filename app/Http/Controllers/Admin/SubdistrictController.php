<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubdistrictRequest;
use App\Http\Requests\UpdateSubdistrictRequest;
use App\Models\ElectoralDistrict;
use App\Models\Subdistrict;
use Yajra\DataTables\Facades\DataTables;

class SubdistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $subdistricts = Subdistrict::latest()->get();
            return DataTables::of($subdistricts)
                ->addIndexColumn()
                ->addColumn('electoral_district', function ($row) {
                    return $row->electoral_district ? $row->electoral_district->name : '-';
                })
                ->addColumn('action', 'admin.subdistrict.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.subdistrict.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $electoral_districts = ElectoralDistrict::all();

        return view('admin.subdistrict.create', compact('electoral_districts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubdistrictRequest $request)
    {
        try {
            $attr = $request->validated();

            Subdistrict::create($attr);

            return redirect()->route('admin.subdistrict.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.subdistrict.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subdistrict $subdistrict)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subdistrict $subdistrict)
    {
        $electoral_districts = ElectoralDistrict::all();
        return view('admin.subdistrict.edit', compact('subdistrict', 'electoral_districts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubdistrictRequest $request, Subdistrict $subdistrict)
    {
        try {
            $attr = $request->validated();

            $subdistrict->update($attr);

            return redirect()
                ->route('admin.subdistrict.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.subdistrict.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subdistrict $subdistrict)
    {
        try {
            $subdistrict->delete();

            return redirect()
                ->route('admin.subdistrict.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.subdistrict.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
