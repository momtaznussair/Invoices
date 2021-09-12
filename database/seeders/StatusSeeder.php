<?php

namespace Database\Seeders;

use App\Models\InvoiceStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = ['غير مدفوعة', 'مدفوعة جزئياً','مدفوعة'];
        foreach($statuses as $status)
        {
            InvoiceStatus::create([
                'status_name' => $status,
                'created_by' => 'Momtaz Nussair',
            ]);
        }
    }
}
