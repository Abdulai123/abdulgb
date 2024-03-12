<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'communication_rating' => 'required|integer|between:1,5',
            'product_rating' => 'required|integer|between:1,5',
            'shipping_speed_rating' => 'required|integer|between:1,5',
            'price_rating' => 'required|integer|between:1,5',
            'feedback' => 'required|in:positive,neutral,negative',
            'comment' => 'nullable|string',
        ];
    }
}
