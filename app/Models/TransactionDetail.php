<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{

    use HasFactory;
    protected $table = "transaction_details";
    protected $primaryKey = 'id';
    protected $fillable = [
        'transactions_id', 'products_id', 'qty', 'total'
    ];

    protected $with = ['product'];

    protected $cast = [
        'total' => 'integer',
        'qty' => 'qty'
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'products_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'id', 'transactions_id');
    }
}
