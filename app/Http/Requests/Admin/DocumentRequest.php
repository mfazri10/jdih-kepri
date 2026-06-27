<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // ─── IDENTITAS DOKUMEN ─────────────────────────────
            'type_id'       => 'required|exists:document_types,id',
            'category_id'   => 'nullable|exists:categories,id',
            'title'         => 'required|string|max:5000',
            'number'        => 'required|string|max:50',
            'year'          => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'slug'          => 'nullable|string|max:255|unique:documents,slug,' . $this->route('document')?->id,

            // ─── STATUS & KLASIFIKASI ──────────────────────────
            'status'        => 'nullable|string|in:berlaku,dicabut,tidak_berlaku',
            'teu'           => 'required|string|max:255',
            'subject'       => 'nullable|string|max:255',
            'source'        => 'nullable|string|max:255',
            'signatory'     => 'nullable|string|max:255',
            'place'         => 'nullable|string|max:100',

            // ─── TANGGAL ───────────────────────────────────────
            'date_set'      => 'nullable|date',
            'date_publish'  => 'nullable|date',
            'date_effective'=> 'nullable|date',

            // ─── KONTEN ────────────────────────────────────────
            'abstract'      => 'nullable|string|max:10000',
            'full_text'     => 'nullable|string',
            'language'      => 'nullable|string|max:20',

            // ─── PUBLISH ───────────────────────────────────────
            'is_featured'   => 'nullable',
            'published_at'  => 'nullable|date',

            // ─── RELASI ────────────────────────────────────────
            'themes'        => 'nullable|array',
            'themes.*'      => 'exists:themes,id',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',

            // ─── FILE ──────────────────────────────────────────
            'attachments'   => 'nullable|array',
            'attachments.*' => 'file|max:51200|mimes:pdf,doc,docx,jpg,jpeg,png',
        ];
    }
}
