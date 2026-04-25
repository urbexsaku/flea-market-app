<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'image' => ['required'],
            'categories' => ['required'],
            'condition' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明を255文字以下で入力してください',
            'image.required' => '商品画像をアップロードしてください',
            'categories.required' => 'カテゴリーを選択してください',
            'condition.required' => '商品状態を選択してください',
            'price.required' => '価格を入力してください',
            'price.numeric' => '価格を数値で入力してください',
            'price.min' => '価格を0円以上で入力してください',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('image')) {
                $file = $this->file('image');
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                    $validator->errors()->add('image', 'jpegまたはpng形式の画像を選択してください');
                }
            }
        });
    }
}
