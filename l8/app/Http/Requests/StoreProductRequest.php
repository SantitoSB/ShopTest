<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name'=>'required|unique:products,name',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|integer|exists:categories,id'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'Введите :attribute'
        ];
    }

    public function attributes()
    {
        return [
            'name'=>'название продукта'
        ];
    }
}
