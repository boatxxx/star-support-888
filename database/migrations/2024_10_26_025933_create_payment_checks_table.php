<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->unsigned(); // รหัสยอดขาย
            $table->bigInteger('received_by')->unsigned(); // รหัสผู้เซ็นรับเงิน
            $table->decimal('received_amount', 10, 2); // ยอดเงินที่ได้รับ
            $table->date('received_date'); // วันที่รับเงิน
            $table->timestamps();

            // กำหนด Foreign Key Constraints

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_checks');
    }
}
