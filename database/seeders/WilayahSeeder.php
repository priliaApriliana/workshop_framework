<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{
    public function run()
    {
        // Disable FK checks for PostgreSQL
        DB::statement('SET session_replication_role = replica;');

        // Truncate existing data (in reverse order of dependencies)
        DB::table('kelurahan')->truncate();
        DB::table('kecamatan')->truncate();
        DB::table('kota')->truncate();
        DB::table('provinsi')->truncate();

        // Re-enable FK checks
        DB::statement('SET session_replication_role = DEFAULT;');

        $this->command->info('Importing provinces...');
        $this->importCsv('provinces.csv', 'provinsi', function($row) {
            return ['id' => (int)$row[0], 'nama' => trim($row[1], '"')];
        });

        $this->command->info('Importing regencies...');
        $this->importCsv('regencies.csv', 'kota', function($row) {
            return [
                'id' => (int)$row[0], 
                'provinsi_id' => (int)$row[1], 
                'nama' => trim($row[2], '"')
            ];
        });

        $this->command->info('Importing districts...');
        $this->importCsv('districts.csv', 'kecamatan', function($row) {
            return [
                'id' => (int)$row[0], 
                'kota_id' => (int)$row[1], 
                'nama' => trim($row[2], '"')
            ];
        });

        $this->command->info('Importing villages...');
        $this->importCsv('villages.csv', 'kelurahan', function($row) {
            return [
                'id' => (int)$row[0], 
                'kecamatan_id' => (int)$row[1], 
                'nama' => trim($row[2], '"')
            ];
        });

        $this->command->info('Wilayah import completed!');
    }

    private function importCsv(string $filename, string $table, callable $mapper)
    {
        $path = database_path('data/' . $filename);
        
        if (!file_exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 0, ';'); // Skip header row
        
        $batch = [];
        $count = 0;

        while (($line = fgetcsv($handle, 0, ';')) !== false) {
            if (count($line) < 2) continue;
            
            $batch[] = $mapper($line);
            $count++;

            if (count($batch) >= 500) {
                DB::table($table)->insert($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            DB::table($table)->insert($batch);
        }

        fclose($handle);
        $this->command->info("  -> Inserted $count rows into $table");
    }
}
