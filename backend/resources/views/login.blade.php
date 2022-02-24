<!doctype html>
<html lang="en" class="h-100">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <meta name="description" content="YSKPointWebForm">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta auther="ynemoto">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="h-100">
  <div class="container d-flex justify-content-center align-items-center h-100 text-center">
    <form class="w-50" method="post" action="{{ route('exeLogin') }}">
      @csrf
      <h1 class="h2 mb-3">サインインする</h1>
      @if ($errors->any())
        <ul class="text-danger list-unstyled">
          @foreach ($errors->all() as $error)
          <li>{{ $error }} </li>
          @endforeach
        </ul>
      @endif
      <div class="form-group">
        <input type="text" class="form-control" name="id" placeholder="ID" autofocus value="{{ old('id') }}">
        <input type="password" class="form-control" name="password" placeholder="パスワード">
      </div>
      <button type="submit" class="btn btn-lg btn-primary btn-block">サインイン</button>
    </form>
  </div>
</body>
</html>
