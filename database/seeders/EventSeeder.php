<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            $currentDate = Carbon::now();
            $endDate = Carbon::now()->addMonth();

            $start = $this->generateRandomDate($currentDate, $endDate);
            $end = $this->generateRandomDate($currentDate, $endDate);

            DB::table('events')->insert([
                'title' => 'Event' . $i,
                'start' => $start,
                'end' => $end
            ]);
        }
    }

    private function generateRandomDate($startDate, $endDate)
    {
        $randomTimestamp = mt_rand($startDate->timestamp, $endDate->timestamp);
        $randomDate = Carbon::createFromTimestamp($randomTimestamp);

        return $randomDate->format('Y-m-d H:i:s');
    }
}

