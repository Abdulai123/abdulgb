<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_id'              => 'required|integer',
            'product_name'          => 'required|string|min:3|max:80',
            'product_description'   => 'required|string|max:2500',
            'price'                 => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'quantity'              => 'required|integer|min:1',
            'ship_from'             => 'string|max:50',
            'payment_type'          => 'required|string|min:2|max:7',
            'product_type'          => 'required|string|in:digital,physical',
            'ship_to'               => 'string|max:50',
            'parent_category_id'    => 'required|integer',
            'sub_category_id'       => 'required|integer',
            'return_policy' => 'sometimes|nullable|min:3|max:500',
            'auto_delivery_content' => 'sometimes|nullable|string|max:500',            
            'image_path1' => 'required|image|mimes:jpeg,png,jpg|max:2000|distinct',
            'image_path2' => 'sometimes|image|mimes:jpeg,png,jpg|max:2000|distinct',
            'image_path3' => 'sometimes|image|mimes:jpeg,png,jpg|max:2000|distinct',            
        ];
    }
}

