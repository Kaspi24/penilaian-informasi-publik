<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreJuryRequest;
use App\Http\Requests\UpdateJuryRequest;
use Illuminate\Support\Facades\Redirect;

class JuryController extends Controller
{
    public function index()
    {
        return view('pages.jury.index');
    }

    public function store(StoreJuryRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'work_unit_id'  => 1,
            'name'      => $validated["name"],
            'username'      => $validated["username"],
            'email'         => $validated["email"],
            'role'          => UserRole::JURY,
            'password'      => Hash::make($validated["password"])
        ]);

        return Redirect::back()->with('success', 'Data Juri berhasil disimpan!');
    }

    public function update(UpdateJuryRequest $request)
    {
        $validated = $request->validated();
        
        $user = User::find($request->id);
        
        unset($validated['id']);

        if(!$validated["password"]) {
            unset($validated['password']);
        }

        $user->update($validated);

        return Redirect::back()->with('success', 'Data Juri berhasil diperbarui!');
    }

    public function delete (Request $request)
    {
        $jury = User::find($request->id);
        $jury->delete();
        return Redirect::back()->with('success', 'Data Juri berhasil dihapus!');
    }
}
