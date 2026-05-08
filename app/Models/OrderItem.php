<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['order_id', 'dish_id', 'quantity', 'notes', 'unit_price', 'status'])]
class OrderItem extends Model
{
    use HasFactory;

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'status' => 'string'
    ];

    // Relación con el pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación con el plato
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    // Scope por estado
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope para items pendientes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Calcular subtotal del item
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }

    // Marcar como listo
    public function markAsReady()
    {
        $this->update(['status' => 'ready']);
        $this->order->updateStatusFromItems();
    }

    // Marcar como entregado
    public function markAsDelivered()
    {
        $this->update(['status' => 'delivered']);
        $this->order->updateStatusFromItems();
    }
}