<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Itemとの多対多リレーション（逆方向）
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category');
    }
}
