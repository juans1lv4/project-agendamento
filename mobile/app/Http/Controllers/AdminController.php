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
        $agendamentos = Agendamento::select('id', 'data_horario', 'name_client', 'phone_client')
        ->with('user:id')
        ->orderBy('data_horario', 'desc')
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
        $agendamento->update(['status' => 'cancelado']);

        return response()->json([
            'message' => 'Agendamento cancelado com sucesso!', $agendamento
        ]);
    }
}
