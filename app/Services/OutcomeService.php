<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Outcome;
use Carbon\Carbon;

const Dob = 'date_of_birth';
const Age = 'age';

class OutcomeService
{
    public function getOutcomeText($outcome, $answers): string
    {
        return $this->replacePlaceholders($outcome->conclusion, $answers);
    }

    public function getOutcome(Form $form, array $answers): ?Outcome
    {
        $outcomes = $form->outcomes;

        foreach ($outcomes as $outcome) {
            if ($this->evaluateRules($outcome->rules, $answers)) {
                return $outcome;
            }
        }

        return null;
    }

    public function evaluateRules(array $rules, array $answers): bool
    {
        foreach ($rules['and'] as $rule) {
            if (! $this->evaluateRule($rule, $answers)) {
                return false;
            }
        }

        return true;
    }

    public function processField(string $field, array $answers): string
    {
        $field = str_replace('{{', '', $field);
        $field = str_replace('}}', '', $field);
        if ($field == Age) {
            return $this->calculateAge($answers[Dob]);
        }

        return $answers[$field];
    }

    private function calculateAge(string $dob): int
    {
        $dob = new Carbon($dob);
        $now = new Carbon();
        $interval = $now->diff($dob);

        return $interval->y;
    }

    public function evaluateRule(array $rule, array $answers): bool
    {
        // some fields like {{age}} must be processed differently
        $field = $rule['field'];
        $processedField = $this->processField($field, $answers);

        $operator = $rule['operator'];
        $value = $rule['value'];

        return match ($operator) {
            '==' => $processedField === $value,
            '!=' => $processedField !== $value,
            '>' => $processedField > $value,
            '<' => $processedField < $value,
            '>=' => $processedField >= $value,
            '<=' => $processedField <= $value,
            default => false,
        };
    }

    private function replacePlaceholders(string $conclusion, array $answers): string
    {
        foreach ($answers as $key => $value) {
            $conclusion = str_replace('{{'.$key.'}}', $value, $conclusion);
        }

        return $conclusion;
    }
}
