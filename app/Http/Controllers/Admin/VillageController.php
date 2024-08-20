<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVillageRequest;
use App\Http\Requests\UpdateVillageRequest;
use App\Models\Subdistrict;
use App\Models\Village;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class VillageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view village'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create village'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit village'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete village'), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $villages = Village::latest()->get();
            return DataTables::of($villages)
                ->addIndexColumn()
                ->addColumn('subdistrict', function ($row) {
                    return $row->subdistrict ? $row->subdistrict->name : '-';
                })
                ->addColumn('action', 'admin.village.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.village.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subdistricts = Subdistrict::all();

        return view('admin.village.create', compact('subdistricts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVillageRequest $request)
    {
        try {
            $attr = $request->validated();

            Village::create($attr);

            return redirect()->route('admin.village.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.village.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Village $village)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        $subdistricts = Subdistrict::all();
        return view('admin.village.edit', compact('village', 'subdistricts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVillageRequest $request, Village $village)
    {
        try {
            $attr = $request->validated();

            $village->update($attr);

            return redirect()
                ->route('admin.village.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.village.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Village $village)
    {
        try {
            $village->delete();

            return redirect()
                ->route('admin.village.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.village.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
