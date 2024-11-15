<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Requests\UpdateCandidateRequest;
use App\Models\Candidate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CandidateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view candidate'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create candidate'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit candidate'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete candidate'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $candidates = Candidate::latest()->get();
            return DataTables::of($candidates)
                ->addIndexColumn()
                ->addColumn('candidate', function ($row) {
                    return $row->candidate;
                })
                ->addColumn('type', function ($row) {
                    return $row->type();
                })
                ->addColumn('action', 'admin.candidate.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.candidate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.candidate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        try {
            $attr = $request->validated();

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $filename = $request->file('photo')->hashName();

                $request->file('photo')->storeAs('upload/paslon/', $filename, 'public');

                $attr['photo'] = $filename;
            }

            Candidate::create($attr);

            return redirect()->route('admin.candidate.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.candidate.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        return view('admin.candidate.edit', compact('candidate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        try {
            $attr = $request->validated();

            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $filename = $request->file('photo')->hashName();

                $request->file('photo')->storeAs('upload/paslon/', $filename, 'public');

                $attr['photo'] = $filename;
            }

            $candidate->update($attr);

            return redirect()
                ->route('admin.candidate.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.candidate.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        try {
            $candidate->delete();

            return redirect()
                ->route('admin.candidate.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.candidate.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
