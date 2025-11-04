<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'category_id',
        'buy_price',
        'sell_price',
        'stock',
        'min_stock',
        'image',
        'status'
    ];

    protected $casts = [
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    public function updateStatus(): void
    {
        if ($this->stock == 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->stock <= $this->min_stock) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'available';
        }
        
        $this->save();
    }
}