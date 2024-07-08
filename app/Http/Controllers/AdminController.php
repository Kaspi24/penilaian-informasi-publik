<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.index');
    }

    public function store(StoreAdminRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'work_unit_id'  => 1,
            'name'          => $validated["name"],
            'username'      => $validated["username"],
            'email'         => $validated["email"],
            'role'          => UserRole::ADMIN,
            'password'      => Hash::make($validated["password"])
        ]);

        return Redirect::back()->with('success', 'Data Admin berhasil disimpan!');
    }

    public function update(UpdateAdminRequest $request)
    {
        $validated = $request->validated();

        
        $user = User::find($request->id);
        
        unset($validated['id']);

        if(!$validated["password"]) {
            unset($validated['password']);
        }

        $user->update($validated);

        return Redirect::back()->with('success', 'Data Admin berhasil diperbarui!');
    }

    public function delete (Request $request)
    {
        $admin = User::find($request->id);
        $admin->delete();
        return Redirect::back()->with('success', 'Data Admin berhasil dihapus!');
    }
}