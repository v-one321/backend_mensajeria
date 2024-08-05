<?php

namespace Database\Seeders;

use App\Models\Personas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item = new Personas();
        $item->nombre = "Generico";
        $item->apellido = ".";
        $item->identificacion = "0";
        $item->save();
    }
}
