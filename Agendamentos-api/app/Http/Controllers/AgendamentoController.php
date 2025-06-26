<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendamentoRequest;
use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendamentoController extends Controller
{
    public function horariosDisponiveis(Request $request)
    {
        $request->validate([
            'data' => 'required|date_format:Y-m-d'
        ]);

        $horariosOcupados = Agendamento::whereDate('data_horario', $request->data)
            ->where('status', 'confirmado')
            ->pluck('data_horario')
            ->map(function ($datetime) {
                return Carbon::parse($datetime)->format('H:i');
            });

        $horariosDisponiveis = [];
        $inicio = Carbon::parse($request->data)->setHour(8)->setMinute(0);
        $fim = Carbon::parse($request->data)->setHour(18)->setMinute(0);

        while ($inicio <= $fim) {
            $horaFormatado = $inicio->format('H:i');
            if (!$horariosOcupados->contains($horaFormatado)) {
                $horariosDisponiveis[] = $horaFormatado;
            }
            $inicio->addMinutes(30);
        }

        return response()->json($horariosDisponiveis);
    }

    //utilizei apenas para testar o navegador
    public function index()
    {
        return Agendamento::orderBy('data_horario')->get();
    }

    public function store(AgendamentoRequest $request)
    {
        $dados = $request->validated();

        $dataHora = Carbon::createFromFormat('Y-m-d H:i', $dados['data_horario']);

        $existente = Agendamento::where('data_horario', $dataHora)
            ->where('status', 'confirmado')
            ->exists();

        if ($existente) {
            return response()->json(['message' => 'Horário já ocupado'], 409);
        }

        $agendamento = Agendamento::create([
            'data_horario' => $dataHora,
            'name_client' => $dados['name_client'],
            'phone_client' => $dados['phone_client'],
            'status' => 'confirmado',

        ]);

        return response()->json([
            'message' => 'Agendamento criado!',
            'data' => [
                'id' => $agendamento->id,
                'data_horario' => $dataHora->format('Y-m-d H:i'), // Formato sem segundos
                'name_client' => $agendamento->name_client,
                'phone_client' => $agendamento->phone_client,
                'status' => $agendamento->status,
                // Removemos created_at e updated_at se não forem necessários
            ]
        ], 201);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update(['status' => 'cancelado']);
        $agendamento->save();

        return response()->json(['message' => 'Cancelado!', $agendamento], 200);
    }
}
