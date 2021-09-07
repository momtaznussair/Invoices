<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'status_id',
        'created_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }
}
