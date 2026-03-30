<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * リレーション定義
     */

    // 出品した商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // いいねした商品
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // コメントした商品
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入した商品
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * アクセサ（便利メソッド）
     */

    // フルアドレス（郵便番号 + 住所 + 建物名）
    public function getFullAddressAttribute()
    {
        $address = $this->postal_code . ' ' . $this->address;
        if ($this->building) {
            $address .= ' ' . $this->building;
        }
        return $address;
    }
}
