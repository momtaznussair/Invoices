<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_Date',
        'Due_date',
        'product_id',
        'Amount_collection',
        'Amount_Commission',
        'Discount',
        'Value_VAT',
        'Rate_VAT',
        'Total',
        'status_id',
        'note',
    ];

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function details()
    {
        return $this->hasMany(InvoiceDetails::class);
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachments::class);
    }
}
