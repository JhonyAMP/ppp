<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'fecha', 'nota', 'estudiante_id'];

    public function estudiantes()
    {
        return $this->belongsToMany(CompanyUser::class);
    }
}
