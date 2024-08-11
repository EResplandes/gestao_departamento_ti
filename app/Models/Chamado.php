<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;

    protected $table = 'chamados';

    protected $fillable = [
        'solicitante',
        'descricao',
        'anexo',
        'email',
        'urgencia',
        'tecnico_id',
        'categoria_id',
        'departamento_id',
        'status_id'
    ];

    public function tecnico(){
        return $this->belongsTo(User::class);
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class);
    }

    public function departamento(){
        return $this->belongsTo(Departamento::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
