<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Subdistrict;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view user'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create user'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit user'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete user'), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Request::ajax()) {
            $users = User::withoutRole('super admin')->get();

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('subdistrict', function ($row) {
                    return $row->subdistrict ? $row->subdistrict->name : '-';
                })
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()->toArray() !== [] ? $row->getRoleNames()[0] : '-';
                })
                ->addColumn('action', 'admin.user.include.action')
                ->toJson();
        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        $subdistricts = Subdistrict::latest()->get();

        return view('admin.user.create', compact('roles', 'subdistricts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $attr = $request->validated();
            $attr['password'] = Hash::make($request->password);

            $user = User::create($attr);

            $user->assignRole($request->role);

            return redirect()
                ->route('admin.user.index')
                ->with('success', __('Data berhasil ditambah.'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.user.index')
                ->with('success', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::get();
        $subdistricts = Subdistrict::latest()->get();
        $user->load('roles:id,name');

        return view('admin.user.edit', compact('user', 'roles', 'subdistricts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $attr = $request->validated();

        if (is_null($attr['password'])) {
            unset($attr['password']);
        } else {
            $attr['password'] = bcrypt($attr['password']);
        }

        $user->update($attr);

        $user->syncRoles($request->role);

        return redirect()
            ->route('admin.user.index')
            ->with('success', __('Data berhasil diedit.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.user.index')
            ->with('success', __('Data berhasil dihapus.'));
    }
}
