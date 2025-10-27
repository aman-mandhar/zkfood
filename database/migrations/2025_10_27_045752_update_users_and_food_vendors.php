<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * USERS TABLE (no rename; keep user_role as-is)
         */
        Schema::table('users', function (Blueprint $t) {
            // Ensure helper cols exist (modify-only)
            if (!Schema::hasColumn('users', 'ref_id')) {
                $t->unsignedBigInteger('ref_id')->nullable()->after('ref_code');
            }
            if (!Schema::hasColumn('users', 'city_id')) {
                $t->unsignedBigInteger('city_id')->nullable()->after('user_role');
            }

            // Indexes / Uniques (clean data first if needed)
            if (Schema::hasColumn('users', 'mobile_number')) {
                $t->unique('mobile_number', 'users_mobile_unique');
            }
            if (Schema::hasColumn('users', 'email')) {
                $t->index('email', 'users_email_idx');
            }
            if (Schema::hasColumn('users', 'ref_code')) {
                $t->index('ref_code', 'users_ref_code_idx');
            }

            // Helpful composite indexes
            if (Schema::hasColumn('users', 'city_id') && Schema::hasColumn('users', 'user_role')) {
                $t->index(['city_id','user_role'], 'users_city_role_idx');
            }
            if (Schema::hasColumn('users','location_lat') && Schema::hasColumn('users','location_lng')) {
                $t->index(['city_id','location_lat','location_lng'], 'users_city_geo_idx');
            }
        });

        // FKs (wrapped to avoid breaking other projects on type mismatch)
        try {
            Schema::table('users', function (Blueprint $t) {
                // user_role -> user_roles.id (only if compatible)
                if (Schema::hasColumn('users', 'user_role')) {
                    $t->foreign('user_role', 'users_user_role_fk')
                      ->references('id')->on('user_roles')
                      ->cascadeOnUpdate()->restrictOnDelete();
                }
            });
        } catch (\Throwable $e) { /* skip if types differ (e.g., signed/unsigned) */ }

        try {
            Schema::table('users', function (Blueprint $t) {
                if (Schema::hasColumn('users', 'city_id')) {
                    $t->foreign('city_id', 'users_city_fk')
                      ->references('id')->on('cities')
                      ->nullOnDelete()->cascadeOnUpdate();
                }
                if (Schema::hasColumn('users', 'ref_id')) {
                    $t->foreign('ref_id', 'users_ref_fk')
                      ->references('id')->on('users')
                      ->nullOnDelete()->cascadeOnUpdate();
                }
            });
        } catch (\Throwable $e) { /* ignore FK add errors safely */ }

        /**
         * FOOD_VENDORS TABLE (no split; modify-only)
         */
        Schema::table('food_vendors', function (Blueprint $t) {
            // Ensure FK columns exist
            if (!Schema::hasColumn('food_vendors', 'user_id')) {
                $t->unsignedBigInteger('user_id')->after('token_id');
            }
            if (!Schema::hasColumn('food_vendors', 'city_id')) {
                $t->unsignedBigInteger('city_id')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('food_vendors', 'ref_id')) {
                $t->unsignedBigInteger('ref_id')->nullable()->after('city_id');
            }

            // Indexes / uniques
            if (Schema::hasColumn('food_vendors', 'status')) {
                $t->index('status', 'fv_status_idx');
            }
            if (Schema::hasColumn('food_vendors', 'slug')) {
                $t->unique('slug', 'fv_slug_unique');
            }
            if (Schema::hasColumn('food_vendors', 'gst_no')) {
                $t->unique('gst_no', 'fv_gst_unique');
            }
            if (Schema::hasColumn('food_vendors', 'mobile_no')) {
                $t->index('mobile_no', 'fv_mobile_idx');
            }
            if (Schema::hasColumn('food_vendors','city_id') && Schema::hasColumn('food_vendors','status')) {
                $t->index(['city_id','status'], 'fv_city_status_idx');
            }
        });

        // FKs (wrapped)
        try {
            Schema::table('food_vendors', function (Blueprint $t) {
                if (Schema::hasColumn('food_vendors', 'user_id')) {
                    $t->foreign('user_id', 'fv_user_fk')
                      ->references('id')->on('users')
                      ->cascadeOnDelete()->cascadeOnUpdate();
                }
                if (Schema::hasColumn('food_vendors', 'city_id')) {
                    $t->foreign('city_id', 'fv_city_fk')
                      ->references('id')->on('cities')
                      ->nullOnDelete()->cascadeOnUpdate();
                }
                if (Schema::hasColumn('food_vendors', 'ref_id')) {
                    $t->foreign('ref_id', 'fv_ref_fk')
                      ->references('id')->on('users')
                      ->nullOnDelete()->cascadeOnUpdate();
                }
            });
        } catch (\Throwable $e) { /* ignore FK add errors safely */ }
    }

    public function down(): void
    {
        // USERS rollback (safe)
        Schema::table('users', function (Blueprint $t) {
            foreach (['users_user_role_fk','users_city_fk','users_ref_fk'] as $fk) {
                try { $t->dropForeign($fk); } catch (\Throwable $e) {}
            }
            foreach ([
                'users_mobile_unique','users_email_idx','users_ref_code_idx',
                'users_city_role_idx','users_city_geo_idx'
            ] as $idx) {
                try { $t->dropIndex($idx); } catch (\Throwable $e) {}
            }
            // columns keep as-is (no drops)
        });

        // FOOD_VENDORS rollback (safe)
        Schema::table('food_vendors', function (Blueprint $t) {
            foreach (['fv_user_fk','fv_city_fk','fv_ref_fk'] as $fk) {
                try { $t->dropForeign($fk); } catch (\Throwable $e) {}
            }
            foreach ([
                'fv_slug_unique','fv_gst_unique','fv_mobile_idx',
                'fv_status_idx','fv_city_status_idx'
            ] as $idx) {
                try { $t->dropIndex($idx); } catch (\Throwable $e) {}
            }
            // columns keep as-is (no drops)
        });
    }
};
