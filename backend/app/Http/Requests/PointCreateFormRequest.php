<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointCreateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'ask-year' => 'required|digits:4',
            'ask-month' => 'required|digits:2',
            'ask-date' => 'required|date',
            'attach-point-id.*' => 'required|digits_between:1,2|exists:points,id',
            'attach-point-date.*' => 'required|digits:8'
        ];
    }

    public function messages()
    {

        $pointMessages = [];
        foreach ($this->request->get('attach-point-id') as $key => $value) {
            # code...
            $pointMessages['attach-point-id.' . $key . '.required'] = $key+1 .'行目の:attributeを指定してください。';
            $pointMessages['attach-point-id.' . $key . '.digits_between'] = $key+1 .'行目の:attributeは:min桁から:max桁の間で指定してください。';
            $pointMessages['attach-point-id.' . $key . '.exists'] = $key+1 .'行目に選択された:attributeは正しくありません。';
        }
        foreach ($this->request->get('attach-point-date') as $key => $value) {
            # code...
            $pointMessages['attach-point-date.' . $key . '.required'] = $key+1 .'行目の:attributeを指定してください。';
            $pointMessages['attach-point-date.' . $key . '.digits'] = $key+1 .'行目の:attributeは:digits桁で指定してください。';
            // $pointMessages['attach-point-date' . $key . '.exists'] = $key+1 .'行目に選択された:attributeは正しくありません。';
        }
        $messages = [
            // 'ask-year.required' => ':attribute入れてほしい！',
            // 'attach-point-id.*.required' => 'ポイントID入れてほしい！',
            // $pointMessages,
        ];
        // +を使う

        // +=演算子を使う
        // $messages += $pointMessages;

        // array_mergeを使う
        $messages = array_merge($messages, $pointMessages);

        // $pointMessages = [
        //     'attach-point-id.*.required' => 'ポイントID入れてほしい！'
        // ];

        // $pointMessages['attach-point-id.*.required'] = 'ポイントID入れてほしい！';

        // foreach($errors->get('attach-point-id') as $key => $value){
        //     $pointMessages[]
        // }
        // return [
        //     'ask-year.required' => '申請年入れてほしい！',
        //     // 'attach-point-id.*.required' => 'ポイントID入れてほしい！',
        //     $pointMessages,
        // ];

        return $messages;
    }
}
