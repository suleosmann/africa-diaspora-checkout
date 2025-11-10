<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Unique UUID for identifying member accounts
            $table->uuid('member_uuid')->nullable()->unique()->after('id');

            // Member details
            $table->string('phone')->nullable()->after('email');
            $table->string('industry_affiliation')->nullable()->after('phone');
            $table->string('region')->nullable()->after('industry_affiliation');
            $table->foreignId('membership_type_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('agreed_to_terms')->default(false)->after('membership_type_id');
        });

        // Auto-assign UUID for existing users if any
        \App\Models\User::whereNull('member_uuid')->get()->each(function ($user) {
            $user->update(['member_uuid' => (string) Str::uuid()]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('membership_type_id');
            $table->dropColumn(['member_uuid', 'phone', 'industry_affiliation', 'region', 'agreed_to_terms']);
        });
    }
};
