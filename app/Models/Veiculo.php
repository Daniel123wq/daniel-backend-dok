<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veiculo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable  = [
        "placa",
        "modelo",
        "cor",
        "tipo",'user_id'
    ];
    
    protected $hidden = ['deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
