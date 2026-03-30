<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
    /**
     * マイページトップ（購入した商品 / 出品した商品 一覧）
     * ?page=buy または ?page=sell で切り替え
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 'buy'); // デフォルトは「購入した商品」

        if ($page === 'sell') {
            // 出品した商品一覧
            $items = Item::where('user_id', auth()->id())->with(['condition'])->latest()->paginate(12);

            return view('mypage.index', compact('items', 'page'));
        }

        // 購入した商品一覧（デフォルト）
        $purchases = Purchase::where('user_id', auth()->id())->with(['item.condition', 'item.user'])->latest()->paginate(12);

        return view('mypage.index', compact('purchases', 'page'));
    }

    /**
     * プロフィール編集画面
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール更新処理
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'postal_code'   => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address'       => 'required|string|max:255',
            'building'      => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        // プロフィール画像のアップロード処理
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました！');
    }
}
