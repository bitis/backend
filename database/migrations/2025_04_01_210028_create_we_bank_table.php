<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('we_bank_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->integer('days_of_product_period')->default(7);
            $table->string('product_period')->default('满7天随用随转');
            $table->string('bank_short_name')->comment('extra_info.bank_short_name');
            $table->string('bank_name')->comment('extra_info.bank_short_name');
            $table->decimal('rate_value')->nullable()->comment('成立以来收益率 rate_value');
            $table->decimal('unit_net_value', 8, 6)->nullable()->comment('当前净值 unit_net_value');
            $table->decimal('adjust_unit_net_value', 8, 6)->nullable()->comment('当前净值 adjust_unit_net_value');
            $table->double('fund_begin_yield', 5, 2)->nullable()->comment('成立以来年化收益率 ladder_rate.fundbeginyield');
            $table->double('month_yield', 5, 2)->nullable()->comment('近一个月年化收益率 ladder_rate.monthyield');
            $table->double('season_yield', 5, 2)->nullable()->comment('近三个月年化收益率 ladder_rate.seasonyield');
            $table->double('month', 5, 2)->nullable()->comment('近一个月收益率 ladder_rate.month');
            $table->double('three_month', 5, 2)->nullable()->comment('近三个月收益率 ladder_rate.threemonth');
            $table->double('half_year_yield', 5, 2)->nullable()->comment('近六个月年化收益率 ladder_rate.seasonyield');
            $table->double('six_month', 5, 2)->nullable()->comment('近六个月收益率 ladder_rate.sixmonth');
            $table->double('twelve_month_yield', 5, 2)->nullable()->comment('近一年年化收益率 ladder_rate.seasonyield');
            $table->timestamp('start_buy_time')->nullable()->comment('');
            $table->double('daily_increase_money')->nullable()->comment('万份收益 当日');
            $table->double('daily_increase_change')->nullable()->comment('日净值变动');
            $table->double('month_increase_money')->nullable()->comment('万份收益 当月');
            $table->double('pre_month_increase_money')->nullable()->comment('万份收益 上月');
            $table->date('value_date')->nullable()->comment('净值日期 ');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('we_bank_stock_rates', function (Blueprint $table) {
            $table->id();
            $table->string('prod_code');
            $table->date('earnings_rate_date');
            $table->decimal('accu_net_value', 8, 6);
            $table->decimal('unit_net_value', 8, 6);
            $table->decimal('daily_increase_change', 8, 6)->nullable();
            $table->double('fund_begin_yield', 5, 2)->nullable();
            $table->double('month_yield', 5, 2)->nullable();
            $table->double('season_yield', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('we_bank_stocks');
        Schema::dropIfExists('we_bank_stock_rates');
    }
};
