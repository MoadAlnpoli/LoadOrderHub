<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mod_conflicts', function (Blueprint $table) {
            if (Schema::hasColumn('mod_conflicts', 'reason')) {
                $table->dropColumn('reason');
            }
            $table->text('reason_en')->nullable()->after('conflicts_with_mod_id');
            $table->text('reason_ar')->nullable()->after('reason_en');
        });
    }

    public function down(): void
    {
        Schema::table('mod_conflicts', function (Blueprint $table) {
            $table->dropColumn(['reason_en', 'reason_ar']);
            $table->string('reason')->nullable()->after('conflicts_with_mod_id');
        });
    }
};
