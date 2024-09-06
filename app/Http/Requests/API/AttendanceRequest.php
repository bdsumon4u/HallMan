<?php

namespace App\Http\Requests\API;

use App\Models\Log;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'hall_id' => 'required|integer|exists:halls,id',
            'student_id' => 'nullable|integer|exists:students,id',
            'action' => 'nullable|in:in,out',
        ];
    }

    protected function prepareForValidation()
    {
        if (! $this->filled('action')) {
            $this->merge([
                'action' => Log::query()
                    ->where('student_id', $this->student_id)
                    ->latest('id')
                    ->first()?->action == 'in' ? 'out' : 'in',
            ]);
        }
    }
}
