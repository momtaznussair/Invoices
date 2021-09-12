<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            //roles
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            //reports
            'reports',
            'invoices reports',
            'users reports',

            //invoices
            'invoices',
            'list invoices',
            'paid invoices',
            'partially paid invoices',
            'unpaid invoices',
            'invoices archive',
            'add invoice',
            'edit invoice',
            'delete invoice',
            'export invoice',
            'print invoice',
            'archive invoice',
            'change invoice status',

            'view invoice details',
            'view invoice attachment',
            'download invoice attachment',
            'add invoice attachment',
            'delete invoice attachment',

            //users
            'users',
            'users list',
            'add user',
            'edit user',
            'delete user',

            //permissions
            'add permission',
            'edit permission',
            'delete permission',
            'show permission',

            //setting
            'setting',

            //sections
            'sections',
            'add section',
            'edit section',
            'delete section',

            //products
            'products',
            'add product',
            'edit product',
            'delete product',

            //payment statuses
            'statuses',
            'add status',
            'edit status',
            'delete status',
            
            //notifications
            'notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
