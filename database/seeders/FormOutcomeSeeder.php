<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Form; // Ensure you use the correct namespace for your Form model

class FormOutcomeSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedMigraineTrialOutcomes();
        $this->seedMentalHealthTrialOutcomes();
    }

    public function seedMigraineTrialOutcomes(): void
    {
        $form = Form::where('name', 'Migraine Trial Questionnaire')->first();

        $outcomesJson = [
            [
                'name' => 'ineligible',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '<',
                            'value' => 18,
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} is not eligible for the trial',
            ],
            [
                'name' => 'cohort_a',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '>=',
                            'value' => 18,
                        ],
                        [
                            'field' => 'migraine_frequency',
                            'operator' => '!=',
                            'value' => 'daily',
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} is assigned to Cohort A',
            ],
            [
                'name' => 'cohort_b',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '>=',
                            'value' => 18,
                        ],
                        [
                            'field' => 'migraine_frequency',
                            'operator' => '==',
                            'value' => 'daily',
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} is assigned to Cohort B',
            ],
        ];

        foreach ($outcomesJson as $outcomeData) {
            $form->outcomes()->create([
                'name' => $outcomeData['name'],
                'rules' => $outcomeData['rules'],
                'conclusion' => $outcomeData['conclusion'],
            ]);
        }
    }
    public function seedMentalHealthTrialOutcomes(): void
    {
        $form = Form::where('name', 'Mental Health Questionnaire')->first();

        $outcomesJson = [
            [
                'name' => 'ineligible',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '<',
                            'value' => 18,
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} is not eligible for the trial',
            ],
            [
                'name' => 'participating',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '>=',
                            'value' => 18,
                        ],
                        [
                            'field' => 'mental_health_condition',
                            'operator' => '==',
                            'value' => 'yes',
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} will be participating in the trial.',
            ],
            [
                'name' => 'not_participating',
                'rules' => [
                    'and' => [
                        [
                            'field' => '{{age}}',
                            'operator' => '>=',
                            'value' => 18,
                        ],
                        [
                            'field' => 'mental_health_condition',
                            'operator' => '==',
                            'value' => 'no',
                        ]
                    ]
                ],
                'conclusion' => 'Participant {{first_name}} will not be participating in the trial.',
            ]
        ];

        foreach ($outcomesJson as $outcomeData) {
            $form->outcomes()->create([
                'name' => $outcomeData['name'],
                'rules' => $outcomeData['rules'],
                'conclusion' => $outcomeData['conclusion'],
            ]);
        }
    }
}

