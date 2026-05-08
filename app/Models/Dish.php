<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

#[Fillable(['category_id', 'name', 'description', 'price', 'image', 'is_highlighted', 'is_available', 'preparation_time', 'order'])]

class Dish extends Model
{
    use HasFactory, Notifiable;

    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return [
        'price' => 'decimal:2',
        'is_highlighted' => 'boolean',
        'is_available' => 'boolean',
        'preparation_time' => 'integer',
        'order' => 'integer'
        ];
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }
     public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
        // Accessor para imagen (URL completa)
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
