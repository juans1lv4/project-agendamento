<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;



class AgendamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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


    public function index()
    {
        return Agendamento::orderBy('data_horario')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate(Agendamento::rules());

        // converter data e hora para dateTime
        $dataHora = Carbon::createFromFormat('Y-m-d H:i', $dados['data_horario']);

        // verificar se existe horarios ocupados
        $existente = Agendamento::where('data_horario', $dataHora)
            ->where('status', 'confirmado')
            ->exists();

        if ($existente) {
            return response()->json(['message' => 'Horario já ocupado'], 409);
        }

        $agendamento = Agendamento::create([
            'data_horario' => $dataHora,
            'name_client' => $dados['name_client'],
            'phone_client' => $dados['phone_client'],
        ]);
        //return response()->json($agendamento, 201);
        Agendamento::create($dados);
        return response()->json(['message' => 'Agendamento criado!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->update(['status' => 'cancelado']);

        return response()->json(['message' => 'Cancelado!'], 201);
        return response()->noContent();
    }
    
}
