<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->string('sha256_hash', 64)->nullable()->after('mime_type');
            $table->json('file_tree_json')->nullable()->after('sha256_hash');
            $table->boolean('is_scanned')->default(false)->after('file_tree_json');
            $table->string('security_status', 32)->default('clean')->after('is_scanned');
            $table->timestamp('scanned_at')->nullable()->after('security_status');
        });
    }

    public function down(): void
    {
        Schema::table('project_files', function (Blueprint $table) {
            $table->dropColumn([
                'sha256_hash',
                'file_tree_json',
                'is_scanned',
                'security_status',
                'scanned_at',
            ]);
        });
    }
};
