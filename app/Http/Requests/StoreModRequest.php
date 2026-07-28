<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreModRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string|max:2000',
            'description_ar' => 'nullable|string|max:2000',
            'game_id' => 'required|exists:games,id',
            'category_id' => 'required|exists:categories,id',
            'steam_url' => 'nullable|url|max:500',
            'nexus_url' => 'nullable|url|max:500',
            'image_url' => 'nullable|url|max:1000',
            'image_file' => 'nullable|image|max:2048', // 2MB max cover image upload
            'conflicts' => 'nullable|array',
            'conflicts.*' => 'exists:mods,id',
            'conflict_reasons_en' => 'nullable|array',
            'conflict_reasons_ar' => 'nullable|array',
        ];
    }
}
