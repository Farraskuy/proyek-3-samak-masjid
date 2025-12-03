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
            // Role (Admin only)
            ['name' => 'view_roles', 'group' => 'Role'],
            ['name' => 'create_roles', 'group' => 'Role'],
            ['name' => 'edit_roles', 'group' => 'Role'],
            ['name' => 'delete_roles', 'group' => 'Role'],

            // Master Data (Admin view only)
            ['name' => 'view_master_data', 'group' => 'Master Data'],

            // Konsultasi (Humas)
            ['name' => 'view_consultations', 'group' => 'Konsultasi'],
            ['name' => 'reply_consultations', 'group' => 'Konsultasi'],
            ['name' => 'delete_consultations', 'group' => 'Konsultasi'],

            // Kegiatan (Humas)
            ['name' => 'view_events', 'group' => 'Kegiatan'],
            ['name' => 'create_events', 'group' => 'Kegiatan'],
            ['name' => 'edit_events', 'group' => 'Kegiatan'],
            ['name' => 'delete_events', 'group' => 'Kegiatan'],

            // Postingan (Humas & Koordinator)
            ['name' => 'view_posts', 'group' => 'Postingan'],
            ['name' => 'create_posts', 'group' => 'Postingan'],
            ['name' => 'edit_posts', 'group' => 'Postingan'],
            ['name' => 'delete_posts', 'group' => 'Postingan'],
            ['name' => 'approve_posts', 'group' => 'Postingan'],

            // Galeri (Humas)
            ['name' => 'view_gallery', 'group' => 'Galeri'],
            ['name' => 'create_gallery', 'group' => 'Galeri'],
            ['name' => 'edit_gallery', 'group' => 'Galeri'],
            ['name' => 'delete_gallery', 'group' => 'Galeri'],

            // Static Page (Humas)
            ['name' => 'view_pages', 'group' => 'Static Page'],
            ['name' => 'edit_pages', 'group' => 'Static Page'],

            // Keuangan (Bendahara)
            ['name' => 'view_finance', 'group' => 'Keuangan'],
            ['name' => 'manage_income', 'group' => 'Keuangan'], // Uang masuk
            ['name' => 'manage_expense', 'group' => 'Keuangan'], // Uang keluar
            ['name' => 'verify_donation', 'group' => 'Keuangan'], // Konfirmasi donasi

            // Bank (Bendahara)
            ['name' => 'view_banks', 'group' => 'Bank'],
            ['name' => 'create_banks', 'group' => 'Bank'],
            ['name' => 'edit_banks', 'group' => 'Bank'],
            ['name' => 'delete_banks', 'group' => 'Bank'],

            // Barang Hilang (Sarpras)
            ['name' => 'view_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'create_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'edit_lost_items', 'group' => 'Barang Hilang'],
            ['name' => 'delete_lost_items', 'group' => 'Barang Hilang'],

            // Pengguna (Admin)
            ['name' => 'create_users', 'group' => 'Pengguna'],
            ['name' => 'view_users', 'group' => 'Pengguna'],
            ['name' => 'edit_users', 'group' => 'Pengguna'],
            ['name' => 'delete_users', 'group' => 'Pengguna'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // 2. Define Roles and Assign Permissions

        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'Admin'], ['alias' => 'Admin System', 'description' => 'Administrator Sistem']);
        $adminPermissions = Permission::whereIn('group', ['Role', 'Master Data', 'Pengguna'])->get();
        $adminRole->permissions()->sync($adminPermissions);

        // ==========================================
        // PERBAIKAN: Humas (Staf Biasa)
        // ==========================================
        $humasRole = Role::firstOrCreate(['name' => 'Humas'], ['alias' => 'Bidang Humas & Publikasi', 'description' => 'Mengelola konten, publikasi, dan konsultasi']);
        
        // Humas manages Konsultasi, Kegiatan, Postingan, Galeri, Static Page
        // TAPI KITA EXCLUDE: approve_posts DAN delete_posts
        $humasPermissions = Permission::whereIn('group', ['Konsultasi', 'Kegiatan', 'Postingan', 'Galeri', 'Static Page'])
            ->whereNotIn('name', ['approve_posts', 'delete_posts']) // <--- Humas tidak bisa Approve & Delete Post
            ->get();
            
        $humasRole->permissions()->sync($humasPermissions);

        // Bendahara
        $bendaharaRole = Role::firstOrCreate(['name' => 'Bendahara'], ['alias' => 'Bendahara', 'description' => 'Mengelola keuangan']);
        $bendaharaPermissions = Permission::whereIn('group', ['Keuangan', 'Bank'])->get();
        $bendaharaRole->permissions()->sync($bendaharaPermissions);

        // Divisi Sarana & Prasarana
        $sarprasRole = Role::firstOrCreate(['name' => 'Sarpras'], ['alias' => 'Divisi Sarana & Prasarana', 'description' => 'Mengelola aset dan barang hilang']);
        $sarprasPermissions = Permission::whereIn('group', ['Barang Hilang'])->get();
        $sarprasRole->permissions()->sync($sarprasPermissions);

        // Jamaah (Existing)
        $jamaahRole = Role::firstOrCreate(['name' => 'Jamaah'], ['alias' => 'Jamaah', 'description' => 'Pengguna Umum']);

        // ==========================================
        // Koordinator Humas
        // ==========================================
        $koorHumasRole = Role::firstOrCreate(
            ['name' => 'Koordinator Humas'], 
            [
                'alias' => 'Koor Humas', 
                'description' => 'Bertanggung jawab memvalidasi dan menyetujui konten postingan sebelum dipublikasikan'
            ]
        );
        
        // Permission spesifik: view, approve, delete (sesuai request)
        $koorPermissions = Permission::whereIn('name', [
            'view_posts', 
            'approve_posts', 
            'delete_posts'
        ])->get();
        
        $koorHumasRole->permissions()->sync($koorPermissions);
    }
}