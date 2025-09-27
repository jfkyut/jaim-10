<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMusicRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'generated_by' => 'nullable|string|max:255',
            'generated_at' => 'nullable|date',
            'description' => 'nullable|string',
            'lyrics' => 'nullable|string',
            'music' => 'required|file|mimes:mp3,wav,ogg|max:30720', // Max 30MB
            'album_id' => 'nullable|exists:albums,id',
        ];
    }
}
