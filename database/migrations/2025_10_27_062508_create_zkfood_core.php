<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /**
         * 1) User addresses (checkout/delivery)
         */
        Schema::create('food_addresses', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->index();
            $t->string('label')->nullable(); // Home/Work etc.
            $t->string('line1');
            $t->string('line2')->nullable();
            $t->unsignedBigInteger('city_id')->nullable()->index(); // maps to cities.id
            $t->string('pincode', 10)->nullable()->index();
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->boolean('is_default')->default(false)->index();
            $t->timestamps();

            // FKs (loose — existing tables, no rename)
            $t->foreign('user_id')->references('id')->on('users')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('city_id')->references('id')->on('cities')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        /**
         * 2) Vendor timings & holidays (per FoodVendor)
         */
        Schema::create('food_vendor_hours', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('vendor_id')->index(); // food_vendors.id
            $t->unsignedTinyInteger('day_of_week'); // 0=Sun ... 6=Sat
            $t->time('open_time')->nullable();
            $t->time('close_time')->nullable();
            $t->boolean('is_open')->default(true);
            $t->timestamps();

            $t->unique(['vendor_id','day_of_week'],'vh_vendor_day_unique');
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_vendor_holidays', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('vendor_id')->index();
            $t->date('holiday_date')->index();
            $t->string('reason')->nullable();
            $t->timestamps();

            $t->unique(['vendor_id','holiday_date'],'vh_vendor_date_unique');
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        /**
         * 3) Menu: categories, items, variants (per vendor)
         */
        Schema::create('food_categories', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('vendor_id')->index();
            $t->string('name');
            $t->string('slug')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();

            $t->unique(['vendor_id','slug'], 'fc_vendor_slug_unique');
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_items', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('vendor_id')->index();
            $t->unsignedBigInteger('category_id')->nullable()->index();
            $t->string('name');
            $t->string('slug')->nullable()->index();
            $t->text('desc')->nullable();
            $t->decimal('price',10,2);
            $t->decimal('mrp',10,2)->nullable();
            $t->boolean('is_veg')->default(true)->index();
            $t->boolean('is_available')->default(true)->index();
            $t->unsignedSmallInteger('prep_minutes')->nullable();
            $t->string('image')->nullable();
            $t->decimal('tax_rate',5,2)->default(0); // % if needed
            $t->softDeletes();
            $t->timestamps();

            $t->unique(['vendor_id','slug'],'fi_vendor_slug_unique');
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('category_id')->references('id')->on('food_categories')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_item_variants', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('item_id')->index();
            $t->string('label'); // Small/Medium/Large, 500ml/1L etc.
            $t->decimal('price_delta',10,2)->default(0);
            $t->boolean('is_active')->default(true)->index();
            $t->timestamps();

            $t->unique(['item_id','label'],'fiv_item_label_unique');
            $t->foreign('item_id')->references('id')->on('food_items')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        /**
         * 4) Delivery partners (role=7 users के लिए operational info)
         */
        Schema::create('delivery_partners', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->unique(); // one row per user
            $t->unsignedBigInteger('city_id')->nullable()->index(); // service city
            $t->enum('vehicle_type',['bike','scooter','cycle','car','other'])->default('bike');
            $t->enum('online_status',['offline','online','busy'])->default('offline')->index();
            $t->decimal('current_lat',10,7)->nullable();
            $t->decimal('current_lng',10,7)->nullable();
            $t->enum('document_status',['pending','approved','rejected'])->default('pending')->index();
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('city_id')->references('id')->on('cities')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        /**
         * 5) Cart & items
         */
        Schema::create('food_carts', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id')->index();
            $t->unsignedBigInteger('vendor_id')->index(); // locked to a vendor
            $t->unsignedInteger('items_count')->default(0);
            $t->decimal('subtotal',10,2)->default(0);
            $t->decimal('delivery_fee',10,2)->default(0);
            $t->decimal('tax',10,2)->default(0);
            $t->decimal('grand_total',10,2)->default(0);
            $t->enum('status',['active','converted','abandoned'])->default('active')->index();
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_cart_items', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('cart_id')->index();
            $t->unsignedBigInteger('item_id')->index();
            $t->unsignedBigInteger('variant_id')->nullable()->index();
            $t->unsignedInteger('qty')->default(1);
            $t->decimal('unit_price',10,2);
            $t->decimal('line_total',10,2);
            $t->string('notes')->nullable();
            $t->timestamps();

            $t->foreign('cart_id')->references('id')->on('food_carts')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('item_id')->references('id')->on('food_items')
              ->restrictOnDelete()->cascadeOnUpdate();
            $t->foreign('variant_id')->references('id')->on('food_item_variants')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        /**
         * 6) Orders, items, assignment, payments
         */
        Schema::create('food_orders', function (Blueprint $t) {
            $t->id();
            $t->string('order_no', 20)->unique();  // e.g. ZKF-YYYYMM-xxxx
            $t->unsignedBigInteger('user_id')->index();
            $t->unsignedBigInteger('vendor_id')->index();
            $t->unsignedBigInteger('address_id')->nullable()->index();
            $t->enum('payment_status',['pending','paid','failed','refunded'])->default('pending')->index();
            $t->enum('order_status',['placed','accepted','assigned','picked','delivered','cancelled','refunded'])
              ->default('placed')->index();
            $t->decimal('subtotal',10,2)->default(0);
            $t->decimal('delivery_fee',10,2)->default(0);
            $t->decimal('tax',10,2)->default(0);
            $t->decimal('grand_total',10,2)->default(0);
            $t->timestamp('paid_at')->nullable();
            $t->timestamp('cancelled_at')->nullable();
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('vendor_id')->references('id')->on('food_vendors')
              ->restrictOnDelete()->cascadeOnUpdate();
            $t->foreign('address_id')->references('id')->on('food_addresses')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_order_items', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('order_id')->index();
            $t->unsignedBigInteger('item_id')->nullable()->index();     // nullable to allow deleted items
            $t->unsignedBigInteger('variant_id')->nullable()->index();
            $t->string('name_snapshot'); // name at time of order
            $t->unsignedInteger('qty');
            $t->decimal('unit_price',10,2);
            $t->decimal('line_total',10,2);
            $t->timestamps();

            $t->foreign('order_id')->references('id')->on('food_orders')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('item_id')->references('id')->on('food_items')
              ->nullOnDelete()->cascadeOnUpdate();
            $t->foreign('variant_id')->references('id')->on('food_item_variants')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_order_assignments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('order_id')->unique(); // one active assignment per order
            $t->unsignedBigInteger('delivery_partner_id')->nullable()->index();
            $t->enum('status',['waiting','assigned','picked','delivered','failed'])->default('waiting')->index();
            $t->unsignedSmallInteger('eta_minutes')->nullable();
            $t->timestamp('assigned_at')->nullable();
            $t->timestamp('picked_at')->nullable();
            $t->timestamp('delivered_at')->nullable();
            $t->timestamps();

            $t->foreign('order_id')->references('id')->on('food_orders')
              ->cascadeOnDelete()->cascadeOnUpdate();
            $t->foreign('delivery_partner_id')->references('id')->on('delivery_partners')
              ->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_payments', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('order_id')->unique();
            $t->string('provider', 50)->default('razorpay')->index();
            $t->string('provider_order_id')->nullable()->index();
            $t->string('provider_payment_id')->nullable()->index();
            $t->enum('status',['created','captured','failed','refunded'])->default('created')->index();
            $t->decimal('amount',10,2)->default(0);
            $t->string('currency', 10)->default('INR');
            $t->json('payload')->nullable(); // raw gateway payload
            $t->timestamps();

            $t->foreign('order_id')->references('id')->on('food_orders')
              ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('food_webhook_logs', function (Blueprint $t) {
            $t->id();
            $t->string('provider', 50)->index(); // razorpay
            $t->string('event')->nullable()->index(); // payment.captured
            $t->json('body');
            $t->string('signature')->nullable();
            $t->enum('status',['received','processed','failed'])->default('received')->index();
            $t->timestamp('processed_at')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_webhook_logs');
        Schema::dropIfExists('food_payments');
        Schema::dropIfExists('food_order_assignments');
        Schema::dropIfExists('food_order_items');
        Schema::dropIfExists('food_orders');
        Schema::dropIfExists('food_cart_items');
        Schema::dropIfExists('food_carts');
        Schema::dropIfExists('delivery_partners');
        Schema::dropIfExists('food_item_variants');
        Schema::dropIfExists('food_items');
        Schema::dropIfExists('food_categories');
        Schema::dropIfExists('food_vendor_holidays');
        Schema::dropIfExists('food_vendor_hours');
        Schema::dropIfExists('food_addresses');
    }
};
