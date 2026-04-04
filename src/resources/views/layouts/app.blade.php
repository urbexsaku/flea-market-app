<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECH</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
</head>
<body>

<header class="header">
  <div class="header__inner">
    <div class="header__left">
      <a href="/">
        <img class="header__logo" src="{{ asset('images/hearder-logo.png') }}">
      </a>
    </div>

    <div class="header__center">
      <form class="header__form">
        <input type="text" name="keyword" placeholder="なにをお探しですか？">
      </form>
    </div>

    <div class="header__right">
      <nav class="header__nav">
        <ul>
          <li><a href="">ログイン</a></li>
          <li><a href="">マイページ</a></li>
          <li><a href="">出品</a></li>
        </ul>
      </nav>
    </div>
  </div>
</header>
<main>
  @yield('content')
</main>

</body>
</html>