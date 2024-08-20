<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePollingStationRequest;
use App\Http\Requests\UpdatePollingStationRequest;
use App\Models\PollingStation;
use App\Models\Village;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PollingStationController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view polling station'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create polling station'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit polling station'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete polling station'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $pollingstations = PollingStation::latest()->get();
            return DataTables::of($pollingstations)
                ->addIndexColumn()
                ->addColumn('village', function ($row) {
                    return $row->village ? $row->village->name : '-';
                })
                ->addColumn('action', 'admin.polling_station.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.polling_station.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $villages = Village::all();

        return view('admin.polling_station.create', compact('villages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePollingStationRequest $request)
    {
        try {
            $attr = $request->validated();

            PollingStation::create($attr);

            return redirect()->route('admin.pollingstation.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.pollingstation.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PollingStation $pollingstation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PollingStation $pollingstation)
    {
        $villages = Village::all();
        return view('admin.polling_station.edit', compact('pollingstation', 'villages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePollingStationRequest $request, PollingStation $pollingstation)
    {
        try {
            $attr = $request->validated();

            $pollingstation->update($attr);

            return redirect()
                ->route('admin.pollingstation.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.pollingstation.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PollingStation $pollingstation)
    {
        try {
            $pollingstation->delete();

            return redirect()
                ->route('admin.pol$pollingstation.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.pol$pollingstation.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
