<?php

namespace App\Http\Requests\Redeem;

use Illuminate\Foundation\Http\FormRequest;

class StoreRedeemRequest extends FormRequest
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
            'credits' => ['required', 'integer', 'min:1'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'claim_through' => ['nullable', 'string', 'max:255'], // Optional field for claim method
            'claim_number' => ['nullable', 'string', 'max:255'], // Optional field for claim method, e.g., GCash number, bank account number, etc.
            // This can be used to store the specific claim method details
            // such as GCash number, bank account number, etc.
            // You can also add more fields if needed for additional claim details
            // For example, if you want to store a transaction ID or reference number
            // 'transaction_id' => ['nullable', 'string', 'max:255'], // Optional field for transaction ID
            // 'reference_number' => ['nullable', 'string', 'max:255'], // Optional field for reference number
            // You can add more fields as needed
        ];
    }
}
