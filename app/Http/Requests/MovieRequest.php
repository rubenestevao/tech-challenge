<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'year' => ['required', 'date_format:Y'],
            'synopsis' => ['nullable', 'string'],
            'runtime' => ['required', 'integer', 'min:1'],
            'released_at' => ['required', 'date', 'date_format:Y-m-d'],
            'cost' => ['required', 'integer'],
            'genre_id' => [
                'required',
                Rule::exists('genres', 'id')
            ],
        ];
    }
}
