<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Home</title>
  <meta name="description" content="YSKPointWebForm">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta auther="ynemoto">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  <header>
    <div class="navbar navbar-dark bg-dark shadow-sm">
      <div class="container d-flex justify-content-between">
        <a href="#" class="navbar-brand">ホーム</a>
        <a href="{{ route('logout') }}">{{ Auth::user()->name }}</a>
      </div>
    </div>
  </header>
  <div class="container">
    <p class="my-3">
      <a href="./entry">ポイント申請画面へ</a>
    </p>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>申請年</th>
          <th>申請月</th>
          <th>申請年月日</th>
          <th>合計ポイント</th>
        </tr>
      </thead>
      <tbody>
        @foreach($attaches as $attach)
        <tr>
          <td><a href="/edit/{{ $attach->id }}">{{ $attach->id }}</a></td>
          <td>{{ $attach->ask_year }}</td>
          <td>{{ $attach->ask_month }}</td>
          <td>{{ $attach->ask_date }}</td>
          <td>{{ $attach->total_point }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <script>
    sessionStorage.clear();
  </script>
</body>
</html>
