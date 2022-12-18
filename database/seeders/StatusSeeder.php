<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public $arrStatus = [
        'To be process',
        'On the way',
        'Delivered',
    ];

    public function getStatus()
    {
        return $this->arrStatus;
    }

    public function run()
    {
        foreach ($this->arrStatus as $strStatus) {
            Status::create([
                'name' => $strStatus,
                'slug' => Str::slug($strStatus, '-')
            ]);
        }
    }
}
