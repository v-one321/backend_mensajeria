<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $item = new User();
        $item->nombre = "admin";
        $item->email = "admin@mail.com";
        $item->password = bcrypt('12345678');
        $item->rol = 'usuario';
        $item->save();

        $item2 = new User();
        $item2->nombre = "cliente";
        $item2->email = "cliente@mail.com";
        $item2->password = bcrypt('12345678');
        $item2->rol = 'cliente';
        $item2->save();
    }
}
