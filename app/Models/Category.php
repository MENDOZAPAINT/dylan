<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;


#[Fillable(['name', 'description', 'order', 'is_active'])]

class Category extends Model
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
    public function scopeActive($query)
    {
        return $query->orderBy('order', 'asc');

    }
}
