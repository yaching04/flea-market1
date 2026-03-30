<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    /**
     * 商品一覧画面（トップページ）
     * 未ログインでも表示可能
     */
    public function index(Request $request)
    {
        $keyword = $request->query('keyword');

        $query = Item::with(['user', 'condition'])
                    ->whereNull('sold_at')   // 売れていない商品のみ
                    ->latest();

        // キーワード検索
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        $items = $query->paginate(12);

        return view('items.index', compact('items', 'keyword'));
    }

    /**
     * 商品詳細画面
     */
    public function show(Item $item)
    {
        // 売却済みの商品は404にする（任意）
        if ($item->sold_at !== null) {
            abort(404);
        }

        $item->load(['user', 'condition', 'categories', 'likes', 'comments.user']);

        return view('items.show', compact('item'));
    }

    /**
     * 出品フォーム画面
     * （認証必須）
     */
    public function create()
    {
        $conditions = \App\Models\Condition::all();
        $categories = \App\Models\Category::all();

        return view('items.create', compact('conditions', 'categories'));
    }

    /**
     * 出品処理
     */
    public function store(Request $request)
    {
        // バリデーションは後でFormRequestでやるので一旦シンプルに
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'condition_id' => 'required|exists:conditions,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 画像保存（後でstorageに移動）
        $imagePath = $request->file('image')->store('items', 'public');

        Item::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition_id' => $request->condition_id,
            'image_path' => 'storage/' . $imagePath,
        ]);

        return redirect()->route('items.index')->with('success', '商品を出品しました！');
    }

    // 以下は一旦空で残しておく（後で実装）
    public function edit(Item $item) { }
    public function update(Request $request, Item $item) { }
    public function destroy(Item $item) { }
}
