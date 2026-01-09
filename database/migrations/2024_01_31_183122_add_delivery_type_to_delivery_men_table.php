<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryTypeToDeliveryMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->enum('delivery_type', ['individual', 'agent'])->default('individual');
            $table->string('vehicle_plate_number')->nullable();
            $table->string('vehicle_insurance')->nullable();
            $table->string('license')->nullable();

            $table->string('logistic_type')->nullable();
            $table->string('tax')->nullable();
            $table->string('gst_certificate')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('cin_certificate')->nullable();
            $table->string('ein_certificate')->nullable();
            $table->string('abn_certificate')->nullable();
            $table->string('tfn_certificate')->nullable();
            $table->string('acn_certificate')->nullable();
            $table->string('passbook_image')->nullable();
            $table->string('deposit_amount')->default(0);
            $table->boolean('is_deposit_enabled')->default(0);
            $table->string('initial_deposit_receipt')->nullable();


            $table->unsignedBigInteger('vehicle_type_id')->nullable();
            $table->unsignedBigInteger('delivery_man_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_men', 'delivery_type')) {
                $table->dropColumn('delivery_type');
            }
            if (Schema::hasColumn('delivery_men', 'vehicle_plate_number')) {
                $table->dropColumn('vehicle_plate_number');
            }
            if (Schema::hasColumn('delivery_men', 'vehicle_insurance')) {
                $table->dropColumn('vehicle_insurance');
            }
            if (Schema::hasColumn('delivery_men', 'license')) {
                $table->dropColumn('license');
            }
            if (Schema::hasColumn('delivery_men', 'logistic_type')) {
                $table->dropColumn('logistic_type');
            }
            if (Schema::hasColumn('delivery_men', 'tax')) {
                $table->dropColumn('tax');
            }
            if (Schema::hasColumn('delivery_men', 'gst_certificate')) {
                $table->dropColumn('gst_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'pan_card')) {
                $table->dropColumn('pan_card');
            }
            if (Schema::hasColumn('delivery_men', 'cin_certificate')) {
                $table->dropColumn('cin_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'ein_certificate')) {
                $table->dropColumn('ein_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'abn_certificate')) {
                $table->dropColumn('abn_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'tfn_certificate')) {
                $table->dropColumn('tfn_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'acn_certificate')) {
                $table->dropColumn('acn_certificate');
            }
            if (Schema::hasColumn('delivery_men', 'passbook_image')) {
                $table->dropColumn('passbook_image');
            }
            if (Schema::hasColumn('delivery_men', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
            if (Schema::hasColumn('delivery_men', 'is_deposit_enabled')) {
                $table->dropColumn('is_deposit_enabled');
            }
            if (Schema::hasColumn('delivery_men', 'initial_deposit_receipt')) {
                $table->dropColumn('initial_deposit_receipt');
            }
            if (Schema::hasColumn('delivery_men', 'vehicle_type_id')) {
                $table->dropColumn('vehicle_type_id');
            }
            if (Schema::hasColumn('delivery_men', 'delivery_man_id')) {
                $table->dropColumn('delivery_man_id');
            }
        });
    }
}
