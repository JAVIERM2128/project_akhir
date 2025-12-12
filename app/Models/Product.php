<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category', // kolom lama, mungkin untuk kompatibilitas
        'category_id', // kolom baru untuk relasi
        'attributes', // kolom JSON untuk atribut khusus
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'attributes' => 'array', // Cast attributes sebagai array/JSON
    ];

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
