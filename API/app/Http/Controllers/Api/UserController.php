<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Technology;
use App\Http\Controllers\Api\Controller;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        $exercises = $user->exercises()
            ->with('technology')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $technologies = Technology::all();
        $userTech = $user->technologies;

         return response()->json([
            'user' => $user,
            'latest_exercises' => $exercises,
            'all_technologies' => $technologies,
            'user_technologies' => $userTech,
        ]);
    }

    public function formEditarPerfil()
    {
        $user = Auth::user();
        return view('editar', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'biografia' => 'nullable|string|max:500',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->nome = $validated['nome'];
        $user->biografia = $validated['biografia'];

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
                Storage::disk('public')->delete($user->foto_perfil);
            }

            $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
            $user->foto_perfil = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'user' => $user
        ], 200);
    }
}