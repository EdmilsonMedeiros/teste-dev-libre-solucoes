<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar o usuário padrão
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('secret'),
        ]);

        // Criar contatos associados ao usuário
        Contact::factory(14)->create([
            'user_id' => $user->id,
        ])->each(function ($contact) {
            // Criar endereço para cada contato
            Address::factory()->create([
                'contact_id' => $contact->id,
            ]);
        });
    }
}
