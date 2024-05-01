<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewCartRequest extends FormRequest
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
            'name'=>['required','min:2','max:40'],
            'title'=>['required','min:2','max:40'],
            'description'=>['required','min:2','max:40'],
            'linkinsageram'=>['required','min:2','max:40'],
            'linkedin'=>['required','min:2','max:40'],
            'pic'=>['image','mimes:jpg,png,jpeg','max:5000'],
        ];
    }
}
