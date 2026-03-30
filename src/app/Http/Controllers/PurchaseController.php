<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * 商品購入フォーム画面
     * （認証必須）
     */
    public function create(Item $item)
    {
        // すでに売却済みの場合はエラー
        if ($item->sold_at !== null) {
            abort(404, 'この商品はすでに売却済みです');
        }

        // 出品者本人による購入を禁止
        if ($item->user_id === auth()->id()) {
            abort(403, '自分の商品は購入できません');
        }

        return view('purchases.create', compact('item'));
    }

    /**
     * 購入処理
     */
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'payment_method' => 'required|in:convenience,card',
            'postal_code'    => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address'        => 'required|string|max:255',
            'building'       => 'nullable|string|max:255',
        ]);

        // すでに売却済みかチェック
        if ($item->sold_at !== null) {
            return redirect()->route('items.show', $item)->with('error', 'この商品はすでに売却済みです');
        }

        // トランザクションで購入処理
        \DB::transaction(function () use ($request, $item) {
            // 購入レコード作成
            Purchase::create([
                'user_id'        => auth()->id(),
                'item_id'        => $item->id,
                'payment_method' => $request->payment_method,
                'postal_code'    => $request->postal_code,
                'address'        => $request->address,
                'building'       => $request->building,
            ]);

            // 商品を売却済みにする
            $item->update(['sold_at' => now()]);
        });

        return redirect()->route('mypage.index', ['page' => 'buy'])->with('success', '商品を購入しました！');
    }

    // 以下は今回は使用しないので一旦空のまま
    public function index() { }
    public function show(Purchase $purchase) { }
    public function edit(Purchase $purchase) { }
    public function update(Request $request, Purchase $purchase) { }
    public function destroy(Purchase $purchase) { }
}
