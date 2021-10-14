<?php

namespace Buatin\Accurate\Seeders;

use Buatin\Accurate\Models\AccurateSetting;
use Illuminate\Database\Seeder;

class AccurateSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccurateSetting::updateOrCreate([
            'key' => 'code',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'access_token',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'token_type',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'refresh_token',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'expire',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'scope',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'db_id',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'session',
        ]);

        AccurateSetting::updateOrCreate([
            'key' => 'pelanggan_umum',
        ]);
    }
}
