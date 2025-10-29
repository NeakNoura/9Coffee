<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialsSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            ['name' => 'Coffee Beans', 'quantity' => 20, 'unit' => 'kg'],
            ['name' => 'Milk', 'quantity' => 15, 'unit' => 'liters'],
            ['name' => 'Sugar', 'quantity' => 50, 'unit' => 'kg'],
        ];
        foreach ($materials as $material) {
    RawMaterial::updateOrCreate(
        ['name' => trim($material['name'])],
        [
            'name' => trim($material['name']),
            'quantity' => $material['quantity'],
            'unit' => $material['unit']
        ]
    );
}
}
}
