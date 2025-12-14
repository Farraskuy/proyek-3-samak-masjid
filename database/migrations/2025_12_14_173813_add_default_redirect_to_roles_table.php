<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('default_redirect_after_login')->default('/')->after('description');
        });

        // Update existing roles with their appropriate redirects
        \App\Models\Role::where('name', 'Jamaah')->update(['default_redirect_after_login' => '/']);
        \App\Models\Role::where('name', 'Guest')->update(['default_redirect_after_login' => '/']);
        \App\Models\Role::where('name', 'Penanggung Jawab')->update(['default_redirect_after_login' => '/admin/pj-dashboard']);
        
        // All other admin roles redirect to /admin
        \App\Models\Role::whereNotIn('name', ['Jamaah', 'Guest', 'Penanggung Jawab'])
            ->update(['default_redirect_after_login' => '/admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('default_redirect_after_login');
        });
    }
};
