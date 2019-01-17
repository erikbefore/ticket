<?php

use Illuminate\Database\Seeder;
use Seeds\RegionalTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
            SettingsTableSeeder::class,
            SettingsPatch::class,
            BasicPriorities::class,
            BasicStatuses::class,
            CategoriesTableSeeders::class,
            ClosingReasonsSeeder::class,
            RegionalTableSeeder::class,
            UfTableSeeder::class,
            PermissionsSeeder::class,
            TicketOriginSeeder::class,
            TicketTypeSeeder::class,
            ChannelSeeder::class
         ]);
    }
}
