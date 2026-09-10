<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $maleFirstNames = [
            'Abdirahman', 'Abdullahi', 'Mohamed', 'Ahmed', 'Ali', 'Yusuf', 'Omar',
            'Hassan', 'Ibrahim', 'Khalid', 'Farah', 'Hussein', 'Mustafe', 'Abdiqadir',
            'Jamal', 'Nur', 'Said', 'Bashir', 'Liban', 'Abdifatah',
        ];

        $femaleFirstNames = [
            'Amina', 'Fadumo', 'Hodan', 'Khadija', 'Sahra', 'Ayan', 'Maryan', 'Ubah',
            'Nasteha', 'Ikran', 'Fardowsa', 'Halima', 'Zainab', 'Deeqa', 'Faiza',
            'Naima', 'Hawa', 'Asli', 'Rahma', 'Yasmin',
        ];

        $lastNames = [
            'Ali', 'Ahmed', 'Mohamed', 'Hassan', 'Yusuf', 'Omar', 'Abdi', 'Warsame',
            'Farah', 'Nur', 'Ismail', 'Ibrahim', 'Hussein', 'Adan', 'Muse', 'Jama',
            'Elmi', 'Aden', 'Osman', 'Salah',
        ];

        $garoweAreas = [
            'Garowe, Puntland', 'Garowe - Iskoshuban Rd, Puntland', 'Garowe - Wadada Isgaarsiinta, Puntland',
            'Garowe - Tawakal, Puntland', 'Garowe - Horseed, Puntland', 'Garowe - Bulo Watiin, Puntland',
        ];

        $networkPrefixes = ['61', '65', '66', '68', '69', '90'];

        for ($i = 1; $i <= 40; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $firstName = $gender === 'male'
                ? $faker->randomElement($maleFirstNames)
                : $faker->randomElement($femaleFirstNames);
            $lastName = $faker->randomElement($lastNames);

            $prefix = $faker->randomElement($networkPrefixes);

            Patient::create([
                'patient_code' => 'PT-' . date('Y') . '-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'date_of_birth' => $faker->dateTimeBetween('-70 years', '-3 years')->format('Y-m-d'),
                'gender' => $gender,
                'phone' => '+252' . $prefix . $faker->numerify('######'),
                'email' => Str::slug("$firstName $lastName") . $i . '@example.com',
                'address' => $faker->randomElement($garoweAreas),
                'emergency_contact_name' => $faker->randomElement($gender === 'male' ? $femaleFirstNames : $maleFirstNames) . ' ' . $faker->randomElement($lastNames),
                'emergency_contact_phone' => '+252' . $faker->randomElement($networkPrefixes) . $faker->numerify('######'),
                'medical_history' => $faker->boolean(30) ? $faker->sentence() : null,
                'allergies' => $faker->boolean(20) ? $faker->randomElement(['Penicillin', 'Latex', 'Aspirin']) : null,
                'is_active' => true,
            ]);
        }
    }
}
