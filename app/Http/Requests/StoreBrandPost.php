<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandPost extends FormRequest
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
            'brand_name' => 'required|unique:brand|max:15|min:2',
            'brand_url' => 'required',
        ];
    }
    public function messages(){
        return [         
            'brand_name.required' => '品牌名称必填',
            'brand_name.unique' => '品牌名称已存在',
            'brand_name.max' => '品牌名称不能大于15位',
            'brand_name.min' => '品牌名称不能小于2位',
            'brand_url.required'  => '分类名称必填',
        ]; 
    } 
}
