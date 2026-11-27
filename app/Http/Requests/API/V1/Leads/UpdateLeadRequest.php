<?php

namespace App\Http\Requests\API\V1\Leads;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lead = $this->route('lead');

        return $this->user()->can('update', $lead);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:50'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'company' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', Rule::in(array_map(fn ($case) => $case->value, LeadStatus::cases()))],
            'source' => ['sometimes', 'nullable', 'string', Rule::in(array_map(fn ($case) => $case->value, LeadSource::cases()))],
            'value' => ['sometimes', 'nullable', 'numeric', 'between:0,99999999.99'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'assigned_to_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'follow_up_at' => ['sometimes', 'nullable', 'date'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ];
    }
}
