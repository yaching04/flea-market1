<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtech フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <!-- ヘッダー -->
    <header class="header">
        <div class="header-inner">
            <!-- ロゴ -->
            <a href="/" class="logo">COACHTECH</a>

            <!-- 検索バー -->
            <div class="search-box">
                <form action="/" method="GET">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="なにをお探しですか？"class="search-input">
                </form>
            </div>

            <!-- 右側ナビ -->
            <nav class="nav-right">
                <a href="{{ route('mypage.index') }}" class="nav-link">マイページ</a>
                <a href="{{ route('items.create') }}" class="sell-button">出品</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">ログアウト</button>
                </form>
            </nav>
        </div>

        <!-- タブ -->
        <div class="tab-area">
            <a href="/" class="tab {{ !request('page') ? 'active' : '' }}">おすすめ</a>
            <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="tab {{ request('page') === 'sell' ? 'active' : '' }}">マイリスト</a>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main-content">
        @yield('content')
    </main>

</body>
</html>
