<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'credits',
        'claim_through', // Optional field for claim method
        'claim_number', // Optional field for claim method, e.g., GCash number, bank account number, etc.
        // This can be used to store the specific claim method details
        // such as GCash number, bank account number, etc.
        // You can also add more fields if needed for additional claim details
        // For example, if you want to store a transaction ID or reference number
        // 'transaction_id', // Optional field for transaction ID
        // 'reference_number', // Optional field for reference number
        // You can add more fields as needed for your specific requirements
        // 'transaction_id', // Optional field for transaction ID
        // 'reference_number', // Optional field for reference number
        // You can add more fields as needed for your specific requirements
        'user_id',
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(RedeemStatus::class, 'status_id');
    }
}
