<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemProperty;
use Illuminate\Database\Seeder;

class StripeItemSeeder extends Seeder
{
    public function run(): void
    {
        $property = ItemProperty::firstOrCreate(
            ['name' => 'Pacchetti Token'],
            ['active' => true]
        );

        Item::firstOrCreate(
            ['descrizione' => 'Starter Pack (100 Token)'],
            [
                'item_property_id' => $property->id,
                'token' => 100,
                'costo' => 9.99,
                'validity_months' => 12,
                'active' => true,
            ]
        );

        Item::firstOrCreate(
            ['descrizione' => 'Pro Pack (500 Token)'],
            [
                'item_property_id' => $property->id,
                'token' => 500,
                'costo' => 39.99,
                'validity_months' => 12,
                'active' => true,
            ]
        );
    }
}
