<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginFormRequest;

class AuthController extends Controller
{
    //引数にRequestすることで、画面のname属性で指定したデータが取得できる
    public function login(LoginFormRequest $request){
        // dd($request->all());

        // $validated = $request->validate([
        //     'id' => 'required|max:255',
        //     'password' => 'required',
        // ]);

        // $validated = $request->validate([
        //     'id' => ['required', 'max:255'],
        //     'password' => ['required'],
        // ]);

        // LoginFormRequestでValidation済みであるため
        // 送信されたリクエストは正しい

        $credentials = $request->only('id', 'password');
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            // return view('home');
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'login_error' => 'メールアドレスかパスワードが間違っています。',
        ]);
    }

    public function logout(Request $request){

        // ユーザーのセッションから認証情報が削除
        Auth::logout();

        // ユーザーのセッションを無効
        $request->session()->invalidate();

        // CSRFトークンを再生成
        $request->session()->regenerateToken();

        // ログイン画面へリダイレクト
        return redirect('/');
    }
}
