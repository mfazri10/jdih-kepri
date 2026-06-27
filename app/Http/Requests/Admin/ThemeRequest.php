<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ThemeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'slug'        => 'nullable|string|max:120|unique:themes,slug,' . $this->route('theme')?->id,
            'description' => 'nullable|string|max:1000',
            'icon'        => 'nullable|string|max:50',
            'color'       => 'nullable|string|max:7', // hex color
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable',
        ];
    }
}
