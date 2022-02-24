<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Edit</title>
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
      <form method="post">
        @csrf
        <div class="header-information">
          <div class="YearMonthInformation">
            <h3>申請情報</h3>
            <div class="{{ $errors->has('ask-year') ? 'has-error' : '' }}">
              <label for="ask-year">申請年</label>
              <input type="text" id="ask-year" name="ask-year" value="{{ old('ask-year', $attachPoint->ask_year)}}"><br/>
              @if ($errors->has('ask-year'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ask-year') }}</strong>
                  </span>
              @endif
            </div>
            <div class="{{ $errors->first('ask-month') ? 'has-error' : '' }}">
              <label for="ask-month">申請月</label>
              <input type="text" id="ask-month" name="ask-month" value="{{ old('ask-month', $attachPoint->ask_month) }}"><br/>
              @if ($errors->has('ask-month'))
                  <span class="help-block">
                      <strong>{{ $errors->first('ask-month') }}</strong>
                  </span>
              @endif
            </div>
            <div id="total-point">
              <label for="total-point">合計ポイント: <span>{{ $attachPoint->total_point }}</span></label>
            </div>
          </div>
          <div class="inputInformation">
            <h3>入力者情報</h3>
            <div class="{{ $errors->has('ask-date') ? 'has-error': '' }}">
              <label for="ask-date">申請日</label>
              <input type="date" id="ask-date" name="ask-date" value="{{ old('ask-date', $attachPoint->ask_date)}}"><br>
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
            <input type="hidden" name="id" value="{{ $attachPoint->id }}">
          </div>
          <div>
            <button id="delete_btn" type="button">削除する</button>
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
              <th></th>
            </tr>
            @foreach($attachDetailPoints as $key => $attachDetailPoint)
            <tr>
              <!-- TODO -->
              <!-- 登録したデータに紐づく値を取得する -->
              <td>{{ Auth::id() }}</td>
              <td>{{ Auth::user()->name }}</td>
              <td><input type="text" id="attach-point-id{{ $key+1 }}" name="attach-point-id[]" onchange="getPointId(this.value, {{ $key+1 }});" size="1" maxlength="3" value="{{ old('attach-point-id.' . $key, $attachDetailPoint->point_id) }}"></td>
              <td><input type="text" name="attach-point-date[]" size="8" maxlength="8" value="{{ old('attach-point-date.' . $key, $attachDetailPoint->attach_date) }}"></td>
              <td><input type="text" name="point-reason[]" value="{{ old('point-reason.' . $key, $attachDetailPoint->reason) }}"></td>
              <td id="point-name{{ $key+1 }}"> {{ $attachDetailPoint->name }}</td>
              <td id="point-comment{{ $key+1 }}">{{ $attachDetailPoint->comment }}</td>
              <td id="point{{ $key+1 }}">{{ $attachDetailPoint->point }}</td>
              <td><button type="button" onclick="deleteTableRow({{ $key+1 }})">削除</button></td>
              <td><input type="hidden" name="detail_id[]" value="{{ $attachDetailPoint->id }}"></td>
            </tr>
            @endforeach
          </table>
        </div>
        <!-- buttonタグの方がボタン的には良い -->
        <!-- <input type="submit" value="登録する" /> -->
        <button type="button" id="update_btn">登録する</button>
      </form>
    </div>
  </div>
  <script src="{{ asset('js/validation.js') }}"></script>
  <script src="{{ asset('js/app.js')}}"></script>
  <script>
    const attachDetailPoints = @json($attachDetailPoints);
    attachDetailPoints.forEach((attachDetailPoint, index) => {
      totalPointAddOrRemove(Number(attachDetailPoint['point']), true);
      console.log(attachDetailPoint);

      // タイトル
      inputHiddenItem('point-name' + (index+1), 'point-name[]', attachDetailPoint['name']);
      // 摘要
      inputHiddenItem('point-comment' + (index+1), 'point-comment[]', attachDetailPoint['comment']);
      // 付与ポイント
      inputHiddenItem('point' + (index+1), 'point[]', attachDetailPoint['point']);
    });

    const updateBtn = document.getElementById("update_btn");
    updateBtn.addEventListener("click", (event) => {
      if (confirm('登録してもよろしいですか？')) {
        const form = document.getElementsByTagName("form")[0];
        form.action = '{{ route('updatePoint') }}';
        form.submit();
      }
    });

    const deleteBtn = document.getElementById("delete_btn");
    deleteBtn.addEventListener("click", (event) => {
      if (confirm('削除してもよろしいですか？')) {
        const form = document.getElementsByTagName("form")[0];
        form.action = '{{ route('deletePoint') }}';
        form.submit();
      }
    });

  </script>
</body>
</html>
