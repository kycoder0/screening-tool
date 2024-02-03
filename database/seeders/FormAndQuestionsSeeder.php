<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormAndQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an example form
        $form = Form::create([
            'name' => 'Migraine Trial Questionnaire',
            'description' => 'Please fill out the following questionnaire to help us understand your migraine symptoms and determine if you are eligible for our clinical trial.',
        ]);

        // Create example questions associated with the form
        // questions:
        /**
         * 1. Subjects first name
         * 2. subjects date of birth
         * 3. how often do they experience them: ['daily', 'weekly', 'monthly']
         * 4. daily frequency if daily was selected: ['1-2', '3-4', '5+']
         */
        $questions = [
            [
                'name' => 'first_name',
                'label' => 'First Name',
                'text' => 'What is your first name?',
                'input_type' => 'text',
                'validation_rules' => ['required', 'string', 'max:255'],
                'visibility_rule' => 'always_visible',
            ],
            [
                'name' => 'date_of_birth',
                'text' => 'What is your date of birth?',
                'label' => 'Date of Birth',
                'input_type' => 'date',
                'validation_rules' => ['required', 'date'],
                'visibility_rule' => 'always_visible',
            ],
            [
                'name' => 'migraine_frequency',
                'text' => 'How often do you experience migraines?',
                'label' => 'Migraine Frequency',
                'input_type' => 'radio',
                'options' => ['daily', 'weekly', 'monthly'],
                'validation_rules' => ['required'],
                'visibility_rule' => 'always_visible',
            ],
            [
                'name' => 'daily_frequency',
                'text' => 'How many times a day do you experience migraines?',
                'label' => 'Daily Frequency',
                'input_type' => 'radio',
                'options' => ['1-2', '3-4', '5+'],
                'validation_rules' => ['required'],
                'visibility_rule' => ['question_name' => 'migraine_frequency', 'answer' => 'daily']
            ],
        ];

        foreach ($questions as $questionData) {
            $form->questions()->create($questionData);
        }
    }
}
