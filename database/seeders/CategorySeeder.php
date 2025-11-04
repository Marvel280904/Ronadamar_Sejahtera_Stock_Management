<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Lem Cair',
                'description' => 'Lem dalam bentuk cair untuk berbagai material'
            ],
            [
                'name' => 'Lem Stik / Glue Stick',
                'description' => 'Lem padat dalam kemasan stick untuk kertas'
            ],
            [
                'name' => 'Double Tape',
                'description' => 'Perekat dua sisi untuk berbagai keperluan'
            ],
            [
                'name' => 'Lem Epoxy',
                'description' => 'Lem dua komponen untuk material keras'
            ],
            [
                'name' => 'Lem Plastik',
                'description' => 'Khusus untuk material plastik dan PVC'
            ],
            [
                'name' => 'Lem Kayu',
                'description' => 'Perekat khusus untuk kayu dan furniture'
            ],
            [
                'name' => 'Lem Karet / Silikon',
                'description' => 'Perekat elastis untuk karet dan silikon'
            ],
            [
                'name' => 'Lem Metal',
                'description' => 'Perekat kuat untuk logam dan besi'
            ],
            [
                'name' => 'Lem Instant / Super Glue',
                'description' => 'Lem cepat kering untuk perbaikan darurat'
            ],
            [
                'name' => 'Lem Serbaguna',
                'description' => 'Lem yang bisa digunakan untuk berbagai material'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
