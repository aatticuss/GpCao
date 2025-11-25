<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Technology;
use App\Http\Controllers\Api\Controller;

class TechnologyController extends Controller
{
     public function sync(Request $request)
    {
        $request->validate([
            'technologies' => 'required|array',
            'technologies.*' => 'integer|exists:technologies,id',
        ]);

        $user = $request->user();

        $user->technologies()->sync($request->technologies);

        $userTech = $user->technologies()->get();

        return response()->json([
            'message' => 'Tecnologias atualizadas com sucesso!',
            'user_technologies' => $userTech
        ], 200);
    }
}
