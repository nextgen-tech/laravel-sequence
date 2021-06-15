<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSequencePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sequence_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained()
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->date('date');
            $table->unsignedInteger('ordinal_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sequence_periods');
    }
}