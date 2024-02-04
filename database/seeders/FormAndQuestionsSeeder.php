<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormAndQuestionsSeeder extends Seeder
{
    private function seedMigraineTrial(): void
    {
        $form = Form::create([
            'name' => 'Migraine Trial Questionnaire',
            'description' => 'Please fill out the following questionnaire to help us understand your migraine symptoms and determine if you are eligible for our clinical trial.',
        ]);

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
                'visibility_rule' => ['depends_on' => 'migraine_frequency', 'value' => 'daily']
            ],
        ];

        foreach ($questions as $questionData) {
            $form->questions()->create($questionData);
        }
    }

    private function seedMentalHealthTrial(): void
    {
        $form = Form::create([
            'name' => 'Mental Health Questionnaire',
            'description' => 'Please fill out the following questionnaire to help us understand your mental wellbeing and determine if you are eligible for our clinical trial.',
        ]);

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
                'name' => 'mental_health_condition',
                'text' => 'Do you have a diagnosed mental health condition?',
                'label' => 'Mental Health Condition',
                'input_type' => 'radio',
                'options' => ['yes', 'no'],
                'validation_rules' => ['required'],
                'visibility_rule' => 'always_visible',
            ],
            [
                'name' => 'mental_health_condition_details',
                'text' => 'Please provide details of your diagnosed mental health condition',
                'label' => 'Mental Health Condition Details',
                'input_type' => 'text',
                'validation_rules' => ['required', 'string'],
                'visibility_rule' => ['depends_on' => 'mental_health_condition', 'value' => 'yes']
            ],
        ];

        foreach ($questions as $questionData) {
            $form->questions()->create($questionData);
        }
    }

    public function run(): void
    {
        $this->seedMigraineTrial();
        $this->seedMentalHealthTrial();
    }
}
