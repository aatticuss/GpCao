<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Technology;
use App\Models\Exercise;
use App\Models\Answer;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExerciseController extends Controller {

    // retorna as tecnologias do usuario
    public function tecnologiasUsuario(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'tecnologias' => $user->technologies
        ]);
    }

    // lista todos os exercícios do usuário (select)
    public function listar(Request $request)
    {
        $user = $request->user();
        $exercicios = Exercise::where('usuario_id', $user->id)->get();

        return response()->json([
            'exercicios' => $exercicios
        ]);
    }
    
    // crud do exercício (insert)
    public function armazenar(Request $request)
    {
        $request->validate([
            'dificuldade' => 'required|in:facil,medio,dificil',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:255',
            'tecnologia_id' => 'required|integer|exists:technologies,id',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        $tecnologia = Technology::find($request->tecnologia_id);
        if (!$tecnologia) {
            return response()->json(['error' => 'Tecnologia não encontrada'], 404);
        }

        $promptGeracao = "Desenvolva o enunciado de um exercício de programação "
            . "utilizando a linguagem '{$tecnologia->nome}', dificuldade '{$request->dificuldade}', "
            . "e a descrição '{$request->descricao}'. Não responda o exercício.";

        $chaveAPI = env('COHERE_API_KEY');

        if (empty($chaveAPI)) {
            return response()->json(['error' => 'Chave de API não configurada'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $chaveAPI,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.cohere.ai/v1/chat', [
                'model' => 'command-r-plus',
                'temperature' => 0.8,
                'max_tokens' => 600,
                'message' => $promptGeracao
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Erro na API Cohere',
                    'detalhes' => $response->body()
                ], 500);
            }

            $generatedPromptContent = $response->json()['text'] ?? null;

            if (!$generatedPromptContent) {
                return response()->json(['error' => 'Resposta inválida da API Cohere'], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao conectar com a API externa',
                'detalhes' => $e->getMessage()
            ], 500);
        }


        Exercise::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'conteudo_exercicio' => $generatedPromptContent,
            'dificuldade' => $request->dificuldade,
            'usuario_id' => $user->id,
            'tecnologia_id' => $request->tecnologia_id
        ]);

        return response()->json([
            'message' => 'Exercício criado com sucesso',
            'exercicio' => $exercicio
        ], 201);        
    }

    // mostra um exericio específico (select)
    public function mostrar(Request $request, Exercise $exercicio) 
    {
        $user = $request->user();

        $userAnswer = Answer::where('usuario_id', $user->id)
            ->where('exercicio_id', $exercicio->id)
            ->latest()
            ->first();
        
        return response()->json([
            'exercicio' => $exercicio,
            'ultima_resposta' => $userAnswer
        ]);    
    }
    
    // valida a resposta do usuario
    public function responder(Request $request, Exercise $exercicio)
    {
        $request->validate([
            'texto_resposta' => 'required|string|max:1000',
        ]);

        $user = $request->user();

        $respostaUsuario = trim($request->texto_resposta);


       $promptAvaliacao = "Avalie a seguinte resposta do usuário: '{$respostaUsuario}'. "
            . "Forneça uma nota de 0 a 10 e uma avaliação detalhada.";

        $chaveAPI = env('COHERE_API_KEY');
    
        if (empty($chaveAPI)) {
            return response()->json(['error' => 'Chave da API não configurada'], 500);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $chaveAPI,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->post('https://api.cohere.ai/v1/chat', [
                'model' => 'command-r-plus',
                'temperature' => 0.5,
                'max_tokens' => 300,
                'message' => $promptAvaliacao
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'error' => 'Erro na API Cohere',
                    'detalhes' => $response->body()
                ], 500);
            }

            $avaliacaoAPI = $response->json()['text'] ?? null;

            $nota = null;
            if (preg_match('/Nota:\s*(\d+)/i', $avaliacaoAPI, $match)) {
                $nota = (int)$match[1];
            }

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erro ao conectar com API externa',
                'detalhes' => $e->getMessage()
            ], 500);        
        }

        $respostaCriada = Answer::create([
            'exercicio_id' => $exercicio->id,
            'usuario_id' => $user->id,
            'texto_resposta' => $respostaUsuario,
            'nota' => $nota,
            'avaliacao' => $avaliacaoAPI
        ]);

        return response()->json([
            'message' => 'Resposta enviada e avaliada com sucesso',
            'resposta' => $respostaCriada
        ], 201);        
    }    
}