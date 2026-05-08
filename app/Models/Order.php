<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


#[Fillable(['table_id', 'mozo_id', 'total', 'status', 'general_notes'])]
class Order extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'status' => 'string'
        ];
    }

    // Relación con la mesa
    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    // Relación con el mozo
    public function mozo()
    {
        return $this->belongsTo(User::class, 'mozo_id');
    }

        public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope para pedidos activos (no entregados ni cancelados)
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_kitchen', 'ready']);
    }
        // Scope por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope por mozo
    public function scopeByMozo($query, $mozoId)
    {
        return $query->where('mozo_id', $mozoId);
    }

    // Scope por mesa
    public function scopeByTable($query, $tableId)
    {
        return $query->where('table_id', $tableId);
    }
       // Scope pedidos de hoy
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    // Calcular el total automáticamente desde los items
    public function calculateTotal()
    {
        $total = $this->items()->sum(DB::raw('quantity * unit_price'));
        
        $this->total = $total;  // Asignar al atributo
        $this->save();          // Guardar en BD
        
        return $total;

    }
        // Actualizar estado del pedido basado en sus items
    public function updateStatusFromItems()
    {
        $itemsStatus = $this->items()->pluck('status')->unique();

        if ($itemsStatus->contains('pending')) {
            $this->update(['status' => 'in_kitchen']);
        } elseif ($itemsStatus->contains('in_kitchen')) {
            $this->update(['status' => 'in_kitchen']);
        } elseif ($itemsStatus->contains('ready')) {
            $this->update(['status' => 'ready']);
        } elseif ($itemsStatus->every(fn($s) => $s === 'delivered')) {
            $this->update(['status' => 'delivered']);
        }
    }
}
