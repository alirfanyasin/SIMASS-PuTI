<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('app:generate-holidays {--year= : The year to generate holidays for}')]
#[Description('Fetch national holidays from public API and store in database')]
class GenerateHolidays extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startYear = $this->option('year') ?? date('Y');
        $endYear = $startYear + 8;

        $this->info("Fetching holidays from {$startYear} to {$endYear}...");
        $totalCount = 0;

        for ($y = $startYear; $y <= $endYear; $y++) {
            $this->info("Fetching holidays for year {$y}...");
            try {
                // Using API
                $response = Http::get("https://api-harilibur.vercel.app/api?month=0&year={$y}");

                if ($response->successful()) {
                    $holidays = $response->json();
                    $count = 0;

                    if (is_array($holidays)) {
                        foreach ($holidays as $holiday) {
                            if (isset($holiday['is_national_holiday']) && $holiday['is_national_holiday']) {
                                Holiday::updateOrCreate(
                                    ['date' => $holiday['holiday_date']],
                                    [
                                        'name' => $holiday['holiday_name'],
                                        'is_national' => true,
                                    ]
                                );
                                $count++;
                                $totalCount++;
                            }
                        }
                    }
                    $this->info("Successfully generated {$count} national holidays for {$y}.");
                } else {
                    $this->error("Failed to fetch holidays from API for year {$y}. Status: ".$response->status());
                }
            } catch (\Exception $e) {
                $this->error("Exception while fetching holidays for year {$y}: ".$e->getMessage());
            }
        }

        $this->info("Process completed. Total holidays generated: {$totalCount}");
    }
}
