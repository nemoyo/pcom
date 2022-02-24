<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

use App\Models\Point;

class PointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DBファサード(クエリビルダ)を使ってデータベースにテストデータを登録
        //モデルへの fillable や guarded の設定をする必要はありません。
        DB::table('points')->insert([
            'id' => 1,
            'name' => '定時退社(定時退社日)',
            'point' => 100,
            'comment' => '週２日の定時退社日に18時までに退社した。(片方のみ定時退社の場合は対象外)',
            'explanation' => "出向先の定時退社日に合わせる。\n一週間ごとに100ポイントとなるよう調整する。\n定時退社日を振り替えた場合は含まない。例えば4/5（金）に残業するため、4/8（月）に定時退社日を振り替えて、4/8（月）に定時退社した場合は対象外とする。\n月をまたぐ場合は翌月の申請分に含める。例えば、7/31（水）と8/2（金）に定時退社した場合は8月分の申請に含める。\n付与申請書に記入する付与日は水曜日、金曜日のどちらの日付でもよい。",
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]);

        //Eloquentモデルを使ったデータベースにテストデータを登録
        //モデルの作成とfillableの設定が必要
        $point = new Point();
        $point->id = 2;
        $point->name = '地域清掃参加';
        $point->point = 200;
        $point->comment = '地域清掃活動に参加した';
        $point->explanation = '出向先での社屋清掃を含む';
        $point->created_at = new DateTime();
        $point->updated_at = new DateTime();

        $point->save();
    }
}
