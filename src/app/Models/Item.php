<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'description',
        'price',
        'condition_id',
        'image_path',
        'sold_at',
    ];

    /**
     * リレーション定義
     */

    // 出品者（ユーザー）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品の状態
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category');
    }

    // いいね（多対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入情報
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    /**
     * アクセサ（便利メソッド）
     */

    // 売却済みかどうか
    public function getIsSoldAttribute()
    {
        return $this->sold_at !== null;
    }

    // いいね数
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
