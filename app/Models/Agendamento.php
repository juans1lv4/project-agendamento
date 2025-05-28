<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_horario',
        'name_client',
        'phone_client',
        'status',
    ];

    public static function rules()
    {
        return [
            'data_horario' => 'required|date|after:now',
            'name_client' => 'required|string|max:100',
            'phone_client' => 'required|string|min:10|max:20',
        ];
    }
}
