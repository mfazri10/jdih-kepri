<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // permission dicek di route middleware
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|max:20|unique:document_types,code,' . $this->route('document_type')?->id,
            'description' => 'nullable|string|max:1000',
            'parent_id'   => 'nullable|exists:document_types,id',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'nullable',
        ];
    }
}
