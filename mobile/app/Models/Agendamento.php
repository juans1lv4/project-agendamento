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

     public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
