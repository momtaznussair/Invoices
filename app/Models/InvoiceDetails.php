<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class InvoiceDetails extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'invoice_id',
        'status_id',
        'created_by',
        'Payment_Date',
        'note',
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
