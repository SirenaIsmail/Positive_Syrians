<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FillDateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dates:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill the date table with dates';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = Carbon::create(2023, 1, 1);
        $endDate = Carbon::create(2024, 1, 1)->subDay();

        $dates = [];

        while ($startDate <= $endDate) {
            $dates[] = ['date' => $startDate->format('Y-m-d')];
            $startDate->addDay();
        }

        DB::table('dates')->insert($dates);

        $this->info('Dates table filled successfully!');
        return Command::SUCCESS;
    }
}
