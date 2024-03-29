<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionRoleTable extends Migration
{
    public function up(): void
    {
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')
                ->constrained('permissions')
                ->cascadeOnDelete();

            $table->foreignId('role_id')
                ->constrained('roles')
                ->cascadeOnDelete();

            $table->primary(['permission_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_role');
    }
}
