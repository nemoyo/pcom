<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Entry</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="YSKPointWebForm">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta auther="ynemoto">
  <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  <link rel="stylesheet" href="{{ asset('css/style.css')}} ">
</head>
<body>
  <div class="container">
    <header>
      <h1>ホーム</h1>
    </header>
    @if ($errors->any())
      <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <li>{{ $error }} </li>
        @endforeach
      </ul>
    @endif

    <div class="main">
      <form method="POST">
        @csrf
        <div class="header-information">
          <div class="YearMonthInformation">
            <h3>申請情報</h3>
            <div class="{{ $errors->has('ask-year') ? 'has-error' : '' }}">
              <label for="ask-year">申請年</label>
              <input type="text" id="ask-year" name="ask-year" value="{{ old('ask-year')}}"><br/>
              @if ($errors->has('ask-year'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ask-year') }}</strong>
                  </span>
              @endif
            </div>
            <div class="{{ $errors->first('ask-month') ? 'has-error' : '' }}">
              <label for="ask-month">申請月</label>
              <input type="text" id="ask-month" name="ask-month" value="{{ old('ask-month') }}"><br/>
              @if ($errors->has('ask-month'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ask-month') }}</strong>
                  </span>
              @endif
            </div>
            <div id="total-point">
              <label for="total-point">合計ポイント: <span></span></label>
            </div>
          </div>
          <div class="inputInformation">
            <h3>入力者情報</h3>
            <div class="{{ $errors->has('ask-date') ? 'has-error': '' }}">
              <label for="ask-date">申請日</label>
              <input type="date" id="ask-date" name="ask-date" value="{{ old('ask-date')}}"><br>
              @if ($errors->has('ask-date'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ask-date') }}</strong>
                  </span>
              @endif
            </div>
            <div>
              <label for="">申請部署名 <span>{{ $group->group_name }}</span></label>
            </div>
            <div>
              <label for="">申請者 <span id="user_name">{{ Auth::user()->name }}</span></label>
            </div>
            <!-- Laravelに送信するためにhiddenにしてる -->
            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="group-id" value="{{ $group->id }}">
          </div>
        </div>
        <p id="add-list" width="100px"><i class="fas fa-plus-circle fa-lg"></i>リストを追加する</p>
        <div class="list">
          <table border="1" id="target-table">
            <tr>
              <th>ユーザーID</th>
              <th>氏名</th>
              <th>付与マスタID</th>
              <th>付与日</th>
              <th>理由</th>
              <th>タイトル</th>
              <th>摘要</th>
              <th>付与数</th>
              <th></th>
            </tr>
          </table>
        </div>
        <!-- buttonタグの方がボタン的には良い -->
        <!-- <input type="submit" value="登録する" /> -->
        <button type="button" id="store_btn">登録する</button>
      </form>
    </div>
  </div>
  <script src="{{ asset('js/validation.js') }}"></script>
  <script src="{{ asset('js/app.js')}}"></script>
  <script>
    // 画面読み込み時に1回だけリスト追加ボタンを押す
    addTableRow(false);
    // sessionStorageに保存されている場合はlineNumber文だけ実行する
    const sessionLineNumber = window.sessionStorage.getItem('lineNumber');
    if (sessionLineNumber !== null){
      for(let i=0; i<sessionLineNumber-1; i++){
        addTableRow(false);
      }
    }

    const storeBtn = document.getElementById("store_btn");
    storeBtn.addEventListener("click", event => {
      if (confirm('登録してもよろしいですか？')) {
        setFormDataSessionStorage();

        const form = document.getElementsByTagName("form")[0];
        form.action = '{{ route('storePoint') }}';
        form.submit();
      }
    });

  </script>
</body>
</html>
