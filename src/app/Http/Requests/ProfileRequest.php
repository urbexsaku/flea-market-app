<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => ['required', 'max:20'],
            'postal_code' => ['required','regex:/^\d{3}-\d{4}$/'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名を20文字以下で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号をハイフン入り8桁で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('profile_image')){
                $file = $this->file('profile_image');
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)){
                    $validator->errors()->add('profile_image', 'jpegまたはpng形式の画像を選択してください');
                }
            }
        });
    }
}
