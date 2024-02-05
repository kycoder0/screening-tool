<?php

namespace App\Services;

use PHPUnit\Framework\TestCase;

class OutcomeServiceTest extends TestCase
{
    public function testGetOutcomeText()
    {
        $outcomeService = new OutcomeService();
        $outcome = new \stdClass();
        $outcome->conclusion = 'Participant {{first_name}} is assigned to Cohort A';
        $answers = [
            'first_name' => 'John',
        ];
        $this->assertEquals('Participant John is assigned to Cohort A', $outcomeService->getOutcomeText($outcome, $answers));
    }

    public function testEvaluatesAndRules()
    {
        $outcomeService = new OutcomeService();
        $outcome = new \stdClass();
        $outcome->rules = [
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
                ],
            ],
        ];
        $answers = [
            'migraine_frequency' => 'weekly',
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertTrue($outcomeService->evaluateRules($outcome->rules, $answers));
    }

    public function testEvaluatesGtRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => '{{age}}',
            'operator' => '>',
            'value' => 18,
        ];
        $answers = [
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertTrue($outcomeService->evaluateRule($rule, $answers));
    }

    public function testEvaluatesLtRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => '{{age}}',
            'operator' => '<',
            'value' => 18,
        ];
        $answers = [
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertFalse($outcomeService->evaluateRule($rule, $answers));
    }

    public function testEvaluatesEqRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => 'migraine_frequency',
            'operator' => '==',
            'value' => 'daily',
        ];
        $answers = [
            'migraine_frequency' => 'daily',
        ];
        $this->assertTrue($outcomeService->evaluateRule($rule, $answers));
    }

    public function testEvaluatesNeqRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => 'migraine_frequency',
            'operator' => '!=',
            'value' => 'daily',
        ];
        $answers = [
            'migraine_frequency' => 'weekly',
        ];
        $this->assertTrue($outcomeService->evaluateRule($rule, $answers));
    }

    public function testEvaluatesGteRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => '{{age}}',
            'operator' => '>=',
            'value' => 18,
        ];
        $answers = [
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertTrue($outcomeService->evaluateRule($rule, $answers));
    }

    public function testEvaluatesLteRule()
    {
        $outcomeService = new OutcomeService();
        $rule = [
            'field' => '{{age}}',
            'operator' => '<=',
            'value' => 18,
        ];
        $answers = [
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertFalse($outcomeService->evaluateRule($rule, $answers));
    }

    public function testProcessField()
    {
        $outcomeService = new OutcomeService();
        $answers = [
            'first_name' => 'John',
            'date_of_birth' => '2000-01-01',
        ];
        $actualAge = date_diff(date_create($answers['date_of_birth']), date_create('today'))->y;
        $this->assertEquals('John', $outcomeService->processField('{{first_name}}', $answers));
        $this->assertEquals($actualAge, $outcomeService->processField('{{age}}', $answers));
    }

    // TODO: future work - outcome service should handle more complex rules
    public function testEvaluatesOrRules()
    {
        $outcomeService = new OutcomeService();
        $outcome = new \stdClass();
        $outcome->rules = [
            'or' => [
                [
                    'field' => 'migraine_frequency',
                    'operator' => '==',
                    'value' => 'daily',
                ],
                [
                    'field' => 'migraine_frequency',
                    'operator' => '==',
                    'value' => 'weekly',
                ],
            ],
        ];
        $answers = [
            'migraine_frequency' => 'monthly',
        ];
        $this->assertFalse($outcomeService->evaluateRules($outcome->rules, $answers));
    }

    // TODO: should handle nested rules
    public function testEvaluatesNestedRules()
    {
        $outcomeService = new OutcomeService();
        $outcome = new \stdClass();
        $outcome->rules = [
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
                ],
                [
                    'or' => [
                        [
                            'field' => 'migraine_frequency',
                            'operator' => '==',
                            'value' => 'weekly',
                        ],
                        [
                            'field' => 'migraine_frequency',
                            'operator' => '==',
                            'value' => 'monthly',
                        ],
                    ],
                ],
            ],
        ];
        $answers = [
            'migraine_frequency' => 'monthly',
            'date_of_birth' => '2000-01-01',
        ];
        $this->assertTrue($outcomeService->evaluateRules($outcome->rules, $answers));
    }

    // TODO: should handle top level rules
    public function testEvaluatesTopLevelRules()
    {
        $outcomeService = new OutcomeService();
        $outcome = new \stdClass();
        $outcome->rules = [
            'field' => 'migraine_frequency',
            'operator' => '==',
            'value' => 'monthly',
        ];
        $answers = [
            'migraine_frequency' => 'monthly',
        ];
        $this->assertFalse($outcomeService->evaluateRules($outcome->rules, $answers));
    }
}
