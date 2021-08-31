<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_Invoice',
        'status_id',
        'created_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
