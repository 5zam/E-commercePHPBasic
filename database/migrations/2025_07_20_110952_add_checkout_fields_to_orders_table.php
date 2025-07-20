<?php
// الملف: add_checkout_fields_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            $table->string('order_number')->unique()->after('id');
            $table->string('session_id')->nullable()->after('user_id'); // 
            
          
            $table->string('first_name')->after('session_id');
            $table->string('last_name')->after('first_name');
            $table->string('email')->after('last_name');
            $table->string('phone')->after('email');
            
           
            $table->text('address')->after('phone');
            $table->string('city')->after('address');
            $table->string('postal_code')->nullable()->after('city');
            $table->enum('shipping_method', ['standard', 'express', 'overnight'])->default('standard')->after('postal_code');
            $table->decimal('shipping_cost', 8, 2)->default(0)->after('shipping_method');
            
            
            $table->decimal('subtotal', 10, 2)->after('shipping_cost');
            $table->decimal('tax', 8, 2)->default(0)->after('subtotal');
           
            
           
            $table->text('notes')->nullable()->after('total');
           
            
          
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'session_id',
                'first_name',
                'last_name', 
                'email',
                'phone',
                'address',
                'city',
                'postal_code',
                'shipping_method',
                'shipping_cost',
                'subtotal',
                'tax',
                'notes'
            ]);
        });
    }
};