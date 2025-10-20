<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('credit_id')
                ->constrained('credits')
                ->cascadeOnDelete();

            // 🪙 Monto total pagado
            $table->decimal('amount', 15, 2);

            // 🧩 Desglose del pago
            $table->decimal('principal_paid', 15, 2)->default(0); // abono a capital
            $table->decimal('interest_paid', 15, 2)->default(0);  // pago de intereses

            // 💰 Saldo restante después del pago
            $table->decimal('remaining_balance', 15, 2)->nullable();

            // 📅 Fecha efectiva del pago
            $table->date('payment_date')->default(now());

            // 📘 Estado del crédito en ese momento (snapshot completo)
            $table->json('metadata')->nullable();

            // Estado (por si se necesita reversar o validar)
            $table->string('status')->default('confirmed'); // confirmed | pending | reversed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
