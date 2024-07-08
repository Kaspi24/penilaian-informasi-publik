<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AdminUpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.user.index');
    }

    public function update(AdminUpdateUserRequest $request)
    {
        $validated = $request->validated();
        
        $user = User::find($request->id);
        
        unset($validated['id']);

        if(!$validated["password"]) {
            unset($validated['password']);
        }

        if($validated['email'] != $user->email) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        return Redirect::back()->with('success', 'Data Pengguna berhasil diperbarui!');
    }

    public function delete (Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return Redirect::back()->with('success', 'Data Pengguna berhasil dihapus!');
    }
}
