<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointCreateFormRequest;
use App\Models\AttachDetailPoint;
use App\Models\AttachPoint;
use App\Models\Group;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function index(){
        $attachPoints = AttachPoint::where('user_id', Auth::id())->get();
        // $attachPoints = AttachPoint::all();

        // dd($attachPoints);
        return view('home', ['attaches' => $attachPoints]);
    }

    public function show(){
        $group = Group::find(Auth::user()->group_id);
        return view('entry', ['group' => $group]);
    }

    public function edit($id)
    {
        $attachPoint = AttachPoint::find($id);
        $attachDetailPoints = AttachDetailPoint::where('attach_point_id', $attachPoint->id)->get();
        // dd($attachDetailPoints);
        $group = Group::find(Auth::user()->group_id);
        return view('edit',  [
            'group' => $group,
            'attachPoint' => $attachPoint,
            'attachDetailPoints' => $attachDetailPoints,
        ]);
    }

    public function getDetail($id){
        // return [1, 2, 3];
        // return view('entry');

        // return response([1,2,3], 200)
        //     ->header('Content-Type', 'application/json');
        $point = Point::find($id);
        // dd($point);

        // return response($point , 200)
        // ->header('Content-Type', 'application/json');

        return response()->json($point)
        ->header('Content-Type', 'application/json');
    }

    public function create(PointCreateFormRequest $request){
        // public function create(Request $request){
        // dd($request);
        // 以下の書き方でも取得できる
        // dd($request->input('ask-year'));

        // $validated = $request->validate([
        //     'ask-year' => ['required'],
        // ]);

        $point = $request->all();
        // dd($point);
        DB::beginTransaction();
        try {
            //code...
            // dd($point);

            // saveメソッドで保存する
            // ヘッダーテーブルへの登録
            $attachPoint = new AttachPoint();
            $attachPoint->ask_year =$point["ask-year"];
            $attachPoint->ask_month =$point["ask-month"];
            $attachPoint->ask_date =$point["ask-date"];
            $attachPoint->user_group_id =$point["group-id"];
            $attachPoint->user_id =$point["user_id"];
            $attachPoint->total_point =$point["total-point"];

            $attachPoint->save();
            // 詳細テーブルへの登録
            for ($i=0; $i<=count($point["attach-point-id"]) -1 ; $i++){
                // insert(save)するごとにnew演算子でインスタンスを作成する
                $attachPointDetail = new AttachDetailPoint();
                $attachPointDetail->attach_point_id =  $attachPoint->id;
                $attachPointDetail->point_id = $point["attach-point-id"][$i];
                $attachPointDetail->attach_date = $point["attach-point-date"][$i];
                $attachPointDetail->reason = $point["point-reason"][$i];
                $attachPointDetail->name = $point["point-name"][$i];
                $attachPointDetail->comment = $point["point-comment"][$i];
                $attachPointDetail->point = $point["point"][$i];

                $attachPointDetail->save();
            }

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            abort(500);
        }

        return redirect()->route('home');
    }

    // public function update(Request $request) {
    public function update(PointCreateFormRequest $request) {
        // dd($request->all());
        $attachPointRequest = $request->all();
        // dd($attachPointRequest);

        $attachPoint = AttachPoint::find($attachPointRequest['id']);
        // dd($attachPoint);

        DB::beginTransaction();
        try {
            // $attachPoint->fill([
            //     'ask_year' => $request->input('ask-year'),
            // ]);
            $attachPoint->ask_year = $attachPointRequest["ask-year"];
            $attachPoint->ask_month = $attachPointRequest["ask-month"];
            $attachPoint->ask_date = $attachPointRequest["ask-date"];
            $attachPoint->user_group_id = $attachPointRequest["group-id"];
            $attachPoint->user_id = $attachPointRequest["user_id"];
            $attachPoint->total_point =$attachPointRequest["total-point"];

            $attachPoint->save();

            for ($i=0; $i<=count($attachPointRequest["attach-point-id"]) -1 ; $i++){
                if (array_key_exists($i, $attachPointRequest['detail_id'])) {
                    $attachPointDetail = AttachDetailPoint::find($attachPointRequest['detail_id'][$i]);
                }else{
                    $attachPointDetail = new AttachDetailPoint();
                }
                $attachPointDetail->attach_point_id =  $attachPoint->id;
                $attachPointDetail->point_id = $attachPointRequest["attach-point-id"][$i];
                $attachPointDetail->attach_date = $attachPointRequest["attach-point-date"][$i];
                $attachPointDetail->reason = $attachPointRequest["point-reason"][$i];
                $attachPointDetail->name = $attachPointRequest["point-name"][$i];
                $attachPointDetail->comment = $attachPointRequest["point-comment"][$i];
                $attachPointDetail->point = $attachPointRequest["point"][$i];

                $attachPointDetail->save();
            }

            if (array_key_exists("delete_key", $attachPointRequest)) {
                AttachDetailPoint::destroy($attachPointRequest["delete_key"]);
            }

            DB::commit();

        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            abort(500);
        }
        return redirect()->route('home');

    }

    public function delete(Request $request) {

        $attachPointRequest = $request->all();
        // dd($attachPointRequest);

        DB::beginTransaction();
        try {
            // ヘッダー情報
            if (array_key_exists("id", $attachPointRequest)) {
                AttachPoint::destroy($attachPointRequest["id"]);
            }

            // 詳細情報
            // 行にのこっているものと
            if (array_key_exists("detail_id", $attachPointRequest)) {
                AttachDetailPoint::destroy($attachPointRequest["detail_id"]);
            }
            // 行削除ボタンを押したものの削除を行う
            if (array_key_exists("delete_key", $attachPointRequest)) {
                AttachDetailPoint::destroy($attachPointRequest["delete_key"]);
            }

            DB::commit();

        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            abort(500);
        }
        return redirect()->route('home');

    }
}
