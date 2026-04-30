<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Car;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {  
        // Create Admin Account
        User::firstOrCreate([
            'name'             => 'System Admin',
            'email'            => 'reygagag1@gmail.com',
            'password'         => Hash::make('Admin@12345'),
            'role'             => 'admin',
            'email_verified_at' => Carbon::now(),
        ]);

        // Sample test user
        User::firstOrCreate([
            'name'             => 'Juan Dela Cruz',
            'email'            => 'user@ferrari.com',
            'password'         => Hash::make('User@12345'),
            'role'             => 'user',
            'contact_number'   => '09171234567',
            'email_verified_at' => Carbon::now(),
        ]);

        // Ferrari Cars
        // ── IMAGE PLACEHOLDER ──────────────────────────────────────────────
        // For each car below, add an image by:
        //   1. Placing your image file in: storage/app/public/cars/
        //   2. Updating the 'image' field value below to match the filename.
        //
        // Example: place 'ferrari-sf90.jpg' in storage/app/public/cars/
        // Then set: 'image' => 'ferrari-sf90.jpg'
        //
        // Leave 'image' => null if you don't have the image yet.
        // ────────────────────────────────────────────────────────────────────

        $cars = [
            ['name' => 'Ferrari SF90 Stradale',  'price' => 48500000, 'description' => 'The most powerful Ferrari production car ever made. 986 hp hybrid V8 engine.', 'image' => null],
            ['name' => 'Ferrari 296 GTB',         'price' => 32000000, 'description' => 'The new era of Ferrari performance with plug-in hybrid technology.', 'image' => null],
            ['name' => 'Ferrari Roma',            'price' => 22000000, 'description' => 'A modern interpretation of the carefree, pleasurable lifestyle of Dolce Vita Rome.', 'image' => null],
            ['name' => 'Ferrari Portofino M',     'price' => 24500000, 'description' => 'The most versatile Ferrari GT. Open-top driving with grand touring comfort.', 'image' => null],
            ['name' => 'Ferrari F8 Tributo',      'price' => 29000000, 'description' => 'A tribute to the most powerful V8 in Ferrari history. 710 hp twin-turbo.', 'image' => null],
            ['name' => 'Ferrari 812 Superfast',   'price' => 38000000, 'description' => 'The most powerful and fastest road-going Ferrari ever. 789 hp naturally aspirated V12.', 'image' => null],
            ['name' => 'Ferrari GTC4Lusso',       'price' => 35000000, 'description' => 'Four-seater four-wheel drive GT with a V12 engine. The ultimate family Ferrari.', 'image' => null],
            ['name' => 'Ferrari Monza SP1',       'price' => 55000000, 'description' => 'A single-seat Icona series barchetta inspired by legendary racing Ferraris of the 1950s.', 'image' => null],
            ['name' => 'Ferrari LaFerrari Aperta','price' => 280000000,'description' => 'The ultimate open-top hybrid hypercar. 950 hp combined power. Only 210 units ever made.', 'image' => null],
        ];

        foreach ($cars as $car) {
            Car::create(array_merge($car, ['is_available' => true, 'stock' => 1]));
        }
    }
}