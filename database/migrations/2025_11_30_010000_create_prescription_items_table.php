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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asuhan_id')->constrained('ashuhans')->onDelete('cascade');
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->nullOnDelete();
            $table->string('name');
            $table->string('unit')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('qty')->default(1);
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // Migrate existing resep text/JSON from ashuhans into prescription_items
        try {
            $rows = \DB::table('ashuhans')->select('id', 'resep')->whereNotNull('resep')->get();
            foreach ($rows as $r) {
                $resep = $r->resep;
                if (!$resep) continue;

                $decoded = null;
                try { $decoded = json_decode($resep, true); } catch (\Throwable $e) { $decoded = null; }

                if (is_array($decoded)) {
                    foreach ($decoded as $it) {
                        \DB::table('prescription_items')->insert([
                            'asuhan_id' => $r->id,
                            'medicine_id' => isset($it['id']) && is_numeric($it['id']) ? intval($it['id']) : null,
                            'name' => $it['name'] ?? ($it['nama'] ?? substr($resep, 0, 191)),
                            'unit' => $it['unit'] ?? null,
                            'price' => isset($it['price']) ? floatval($it['price']) : null,
                            'qty' => isset($it['qty']) ? intval($it['qty']) : 1,
                            'note' => $it['note'] ?? ($it['catatan'] ?? null),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                } else {
                    \DB::table('prescription_items')->insert([
                        'asuhan_id' => $r->id,
                        'medicine_id' => null,
                        'name' => substr($resep, 0, 191),
                        'unit' => null,
                        'price' => null,
                        'qty' => 1,
                        'note' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // If migration of existing data fails, continue silently — manual migration can be retried.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
