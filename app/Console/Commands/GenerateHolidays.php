<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-holidays {--year= : The year to generate holidays for}')]
#[Description('Fetch national holidays from public API and store in database')]
class GenerateHolidays extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = $this->option('year') ?? date('Y');
        $this->info("Fetching holidays for year {$year}...");

        try {
            $response = \Illuminate\Support\Facades\Http::get("https://api-harilibur.vercel.app/api?month=0&year={$year}");
            
            if ($response->successful()) {
                $holidays = $response->json();
                $count = 0;

                foreach ($holidays as $holiday) {
                    if ($holiday['is_national_holiday']) {
                        \App\Models\Holiday::updateOrCreate(
                            ['date' => $holiday['holiday_date']],
                            [
                                'name' => $holiday['holiday_name'],
                                'is_national' => true
                            ]
                        );
                        $count++;
                    }
                }

                $this->info("Successfully generated {$count} national holidays for {$year}.");
            } else {
                $this->error("Failed to fetch holidays from API. Status: " . $response->status());
            }
        } catch (\Exception $e) {
            $this->error("Exception while fetching holidays: " . $e->getMessage());
        }
    }
}
