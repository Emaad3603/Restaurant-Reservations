<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MealTypeTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all meal types
        $mealTypes = DB::table('meal_types')->get();
        
        // Sample translations
        $translations = [
            'en' => ['Breakfast', 'Lunch', 'Dinner', 'Brunch', 'Dessert', 'All Day'],
            'es' => ['Desayuno', 'Almuerzo', 'Cena', 'Brunch', 'Postre', 'Todo el día'],
            'fr' => ['Petit déjeuner', 'Déjeuner', 'Dîner', 'Brunch', 'Dessert', 'Toute la journée'],
            'de' => ['Frühstück', 'Mittagessen', 'Abendessen', 'Brunch', 'Dessert', 'Den ganzen Tag'],
        ];
        
        // Create translations for each meal type
        foreach ($mealTypes as $index => $mealType) {
            // If we have more meal types than translations, cycle back to the beginning
            $translationIndex = $index % count($translations['en']);
            
            foreach ($translations as $langCode => $translationArray) {
                DB::table('meal_type_translations')->insert([
                    'meal_type_id' => $mealType->meal_type_id,
                    'language_code' => $langCode,
                    'name' => $translationArray[$translationIndex],
                    'description' => 'Sample ' . $translationArray[$translationIndex] . ' description',
                ]);
            }
        }
    }
} 