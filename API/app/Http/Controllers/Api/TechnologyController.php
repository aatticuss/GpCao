<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Technology;
use App\Http\Controllers\Api\Controller;

class TechnologyController extends Controller
{
    public function updateTechnologies(Request $request)
    {
        $validated = $request->validate([
            'technologies' => 'array',
            'technologies.*' => 'integer|exists:technologies,id'
        ]);

        // usuário autenticado (via token)
        $user = $request->user();

        // sincroniza as tecnologias do usuáro com as selecionadas no formulário
        $user->technologies()->sync($validated['technologies'] ?? []);

        return response()->json([
            'message' => 'Tecnologias atualizadas com sucesso.',
            'technologies' => $user->technologies()->get(),
        ], 200);
    }
}
