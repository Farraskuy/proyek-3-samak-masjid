<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Permissions
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'group' => 'Dashboard'],

            // Role (Admin only)
            ['name' => 'view_roles', 'group' => 'Role'],
            ['name' => 'create_roles', 'group' => 'Role'],
            ['name' => 'edit_roles', 'group' => 'Role'],
            ['name' => 'delete_roles', 'group' => 'Role'],

            // Pengguna (Admin)
            ['name' => 'view_users', 'group' => 'Pengguna'],
            ['name' => 'create_users', 'group' => 'Pengguna'],
            ['name' => 'edit_users', 'group' => 'Pengguna'],
            ['name' => 'delete_users', 'group' => 'Pengguna'],

            // Konsultasi
            ['name' => 'view_consultations', 'group' => 'Konsultasi'],
            ['name' => 'reply_consultations', 'group' => 'Konsultasi'],
            ['name' => 'delete_consultations', 'group' => 'Konsultasi'],

            // Kegiatan
            ['name' => 'view_events', 'group' => 'Kegiatan'],
            ['name' => 'create_events', 'group' => 'Kegiatan'],
            ['name' => 'edit_events', 'group' => 'Kegiatan'],
            ['name' => 'delete_events', 'group' => 'Kegiatan'],

            // Postingan
            ['name' => 'view_posts', 'group' => 'Postingan'],
            ['name' => 'create_posts', 'group' => 'Postingan'],
            ['name' => 'edit_posts', 'group' => 'Postingan'],
            ['name' => 'delete_posts', 'group' => 'Postingan'], // Koor & Admin
            ['name' => 'approve_posts', 'group' => 'Postingan'], // Koor & Admin

            // Galeri
            ['name' => 'view_gallery', 'group' => 'Galeri'],
            ['name' => 'create_gallery', 'group' => 'Galeri'],
            ['name' => 'edit_gallery', 'group' => 'Galeri'],
            ['name' => 'delete_gallery', 'group' => 'Galeri'],

            // Static Page
            ['name' => 'view_pages', 'group' => 'Static Page'],
            ['name' => 'edit_pages', 'group' => 'Static Page'], // Create/Edit/Delete

            // Keuangan
            ['name' => 'view_finance', 'group' => 'Keuangan'],
            ['name' => 'manage_income', 'group' => 'Keuangan'], 
            ['name' => 'manage_expense', 'group' => 'Keuangan'], 
            ['name' => 'delete_finance', 'group' => 'Keuangan'], 
            ['name' => 'verify_donation', 'group' => 'Keuangan'], 

            // Bank
            ['name' => 'view_banks', 'group' => 'Bank'],
            ['name' => 'create_banks', 'group' => 'Bank'],
            ['name' => 'edit_banks', 'group' => 'Bank'],
            ['name' => 'delete_banks', 'group' => 'Bank'],

            // Infaq
            ['name' => 'view_infaq', 'group' => 'Infaq'],
            ['name' => 'create_infaq', 'group' => 'Infaq'],
            ['name' => 'edit_infaq', 'group' => 'Infaq'],
            ['name' => 'delete_infaq', 'group' => 'Infaq'],

            // Pengaturan Zakat
            ['name' => 'manage_zakat_settings', 'group' => 'Pengaturan'],

            // Barang Hilang
            ['name' => 'view_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'create_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'edit_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'delete_lost_items', 'group' => 'Barang Hilang'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // 2. Define Roles and Assign Permissions

        // --- Role: Super Admin (Development) ---
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'], 
            ['alias' => 'Super Admin', 'description' => 'Super Administrator untuk Development - Akses ke semua fitur']
        );
        // Super Admin gets ALL permissions
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->sync($allPermissions);


        // --- Role: Admin ---
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'], 
            ['alias' => 'Admin System', 'description' => 'Administrator Sistem']
        );
        // Admin gets specialized permissions + generic dashboard access
        $adminPermissions = Permission::whereIn('group', ['Dashboard', 'Role', 'Pengguna'])->get();
        $adminRole->permissions()->sync($adminPermissions);


        // --- Role: Koordinator Humas ---
        $koorHumasRole = Role::firstOrCreate(
            ['name' => 'Koordinator Humas'], 
            [
                'alias' => 'Koor Humas', 
                'description' => 'Bertanggung jawab memvalidasi dan menyetujui konten postingan sebelum dipublikasikan'
            ]
        );
        $koorPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_posts', 
            'approve_posts', 
            'delete_posts'
        ])->get();
        $koorHumasRole->permissions()->sync($koorPermissions);


        // --- Role: Humas ---
        $humasRole = Role::firstOrCreate(
            ['name' => 'Humas'], 
            ['alias' => 'Bidang Humas & Publikasi', 'description' => 'Mengelola konten, publikasi, dan konsultasi']
        );
        $humasPermissions = Permission::whereIn('group', ['Dashboard', 'Konsultasi', 'Kegiatan', 'Postingan', 'Galeri', 'Static Page'])
            ->whereNotIn('name', ['approve_posts', 'delete_posts'])
            ->get();
        $humasRole->permissions()->sync($humasPermissions);



        // --- Role: Bendahara Pemasukan ---
        $bendaharaMRole = Role::firstOrCreate(
            ['name' => 'Bendahara Pemasukan'], 
            ['alias' => 'Bendahara Pemasukan', 'description' => 'Mengelola keuangan masuk dan Infaq']
        );

        $bendaharaMPermissions = Permission::whereIn('group', ['Dashboard', 'Keuangan', 'Bank', 'Infaq'])
            ->whereNotIn('name', [
                'manage_expense', 
                'delete_finance', 
                'create_banks', 
                'edit_banks', 
                'delete_banks'
            ])
            ->get();
            
        $bendaharaMRole->permissions()->sync($bendaharaMPermissions);



        // --- Role: Bendahara Pengeluaran ---
        $bendaharaKRole = Role::firstOrCreate(
            ['name' => 'Bendahara Pengeluaran'], 
            ['alias' => 'Bendahara Pengeluaran', 'description' => 'Mengelola keuangan keluar']
        );

        $bendaharaKPermissions = Permission::whereIn('group', ['Dashboard', 'Keuangan', 'Bank'])
            ->whereNotIn('name', [
                'manage_income', 
                'verify_donation',
                'delete_finance', 
                'create_banks', 
                'edit_banks', 
                'delete_banks'
            ])
            ->get();
            
        $bendaharaKRole->permissions()->sync($bendaharaKPermissions);


        // --- Role: Koordinator Bendahara ---
        $koorBendaharaRole = Role::firstOrCreate(
            ['name' => 'Koordinator Bendahara'], 
            [
                'alias' => 'Koor Bendahara', 
                'description' => 'Supervisor keuangan, bank, dan infaq'
            ]
        );

        $koorBendaharaPermissions = Permission::whereIn('group', ['Dashboard', 'Keuangan', 'Bank', 'Infaq'])->get();
        $koorBendaharaRole->permissions()->sync($koorBendaharaPermissions);



        // --- Role: Sarpras ---
        $sarprasRole = Role::firstOrCreate(
            ['name' => 'Sarpras'], 
            ['alias' => 'Divisi Sarana & Prasarana', 'description' => 'Mengelola aset dan barang hilang']
        );
        $sarprasPermissions = Permission::whereIn('group', ['Dashboard', 'Barang Hilang'])->get();
        $sarprasRole->permissions()->sync($sarprasPermissions);


        // --- Role: Jamaah (No Dashboard Access) ---
        Role::firstOrCreate(
            ['name' => 'Jamaah'], 
            ['alias' => 'Jamaah', 'description' => 'Pengguna Umum']
        );
    }
}
