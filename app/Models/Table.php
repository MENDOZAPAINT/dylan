<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['number', 'zone', 'capacity', 'status', 'mozo_id', 'is_active'])]
class Table extends Model
{
    use HasFactory;
    protected $table = 'tables';

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'is_active' => 'boolean',
            'capacity' => 'integer'
        ];
    }

    // Relación con el mozo (usuario)
    public function mozo()
    {
        return $this->belongsTo(User::class, 'mozo_id');
    }

    // Relación con pedidos
    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id');
    }
    // Scope para mesas libres
    public function scopeFree($query)
    {
        return $query->where('status', 'free');
    }

    // Scope para mesas ocupadas
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }
    // Scope para mesas activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope por mozo asignado
    public function scopeByMozo($query, $mozoId)
    {
        return $query->where('mozo_id', $mozoId);
    }

    // Cambiar estado a ocupado
    public function occupy()
    {
        $this->update(['status' => 'occupied']);
    }
    // Cambiar estado a libre
    public function free()
    {
        $this->update(['status' => 'free']);
    }
}
