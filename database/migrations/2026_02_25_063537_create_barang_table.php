<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            $table->timestamp('timestamp')->useCurrent();
        });

        // Create function for trigger
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_id_barang()
            RETURNS TRIGGER AS $$
            DECLARE
                nr INTEGER;
            BEGIN
                SELECT COUNT(*) + 1 INTO nr 
                FROM barang 
                WHERE DATE(timestamp) = CURRENT_DATE;
                
                NEW.id_barang := CONCAT(
                    TO_CHAR(CURRENT_TIMESTAMP, 'YY'),
                    TO_CHAR(CURRENT_TIMESTAMP, 'MM'),
                    TO_CHAR(CURRENT_TIMESTAMP, 'DD'),
                    LPAD(nr::TEXT, 2, '0')
                );
                
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Create trigger
        DB::unprepared("
            CREATE TRIGGER trigger_id_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            EXECUTE FUNCTION generate_id_barang();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang ON barang');
        DB::unprepared('DROP FUNCTION IF EXISTS generate_id_barang()');
        Schema::dropIfExists('barang');
    }
};