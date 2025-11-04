<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use App\Traits\QueryScopes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, QueryScopes;

    protected $fillable = [
        'transaction_code',
        'product_id',
        'customer_id',
        'account',
        'amount',
        'status',
        'type',
        'gateway',
        'description',
        'paid_at'
    ];

    public function products(): BelongsTo{
        return $this->belongsTo(Product::class);
    }

    public function customers(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }
}
