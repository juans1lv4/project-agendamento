<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agendamentos = Agendamento::select('id', 'data_horario', 'name_client', 'phone_client', 'status')
            ->with('user:id')
            ->orderBy('data_horario', 'asc')
            ->get();

        return response()->json($agendamentos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function cancelar($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        if ($agendamento->status === 'confirmado') {
            $agendamento->status = 'cancelado';
            $agendamento->save();
        }

        return response()->json([
            'message' => 'Agendamento cancelado com sucesso!',
            'data' => $agendamento
        ], 200);
    }
}
