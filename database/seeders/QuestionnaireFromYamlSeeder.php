<?php

namespace Database\Seeders;

use App\Models\Form;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Symfony\Component\Yaml\Yaml;

class QuestionnaireFromYamlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    private function seedFromYaml(): void
    {
        // Load and parse the YAML file
        $formFile = base_path('database/seeders/yaml/migraine-questionnaire.yaml');
        $formData = Yaml::parseFile($formFile);

        $form = Form::create([
            'name' => $formData['name'],
            'description' => $formData['description'],
        ]);

        foreach ($formData['questions'] as $questionData) {
            $form->questions()->create($questionData);
        }

        foreach ($formData['outcomes'] as $outcomeData) {
            $form->outcomes()->create([
                'name' => $outcomeData['name'],
                'rules' => $outcomeData['rules'],
                'conclusion' => $outcomeData['conclusion'],
            ]);
        }

    }

    public function run(): void
    {
        $this->seedFromYaml();
    }
}
