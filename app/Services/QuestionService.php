<?php

namespace App\Services;

class QuestionService
{
    const ALWAYS_VISIBLE_RULE = 'always_visible';

    public function isVisible($question, $currentAnswers): bool
    {
        if (empty($question->visibility_rule)) {
            return true; // No visibility rules, so the question is always visible
        }

        if ($question->visibility_rule == self::ALWAYS_VISIBLE_RULE) {
            return true; // The question is always visible
        }

        $rules = $question->visibility_rule;
        $dependsOnName = $rules['depends_on'];
        $requiredValue = $rules['value'];

        // Find the ID of the question that $dependsOnName refers to
        return isset($currentAnswers[$dependsOnName]) && $currentAnswers[$dependsOnName] == $requiredValue;
    }

    public function buildValidationRules($questions, $answers)
    {
        $rules = [];
        foreach ($questions as $question) {
            if ($question->validation_rules) {
                $rules["answers.{$question->name}"] = $question->validation_rules;
                // if not visible, remove "required" rule from the rules array
                if (! $this->isVisible($question, $answers)) {
                    // filter out the "required" rule
                    $rules["answers.{$question->name}"] = array_filter($question->validation_rules, function ($rule) {
                        return $rule != 'required';
                    });
                }
            }
        }

        return $rules;
    }

    public function buildValidationMessages($questions): array
    {
        $messages = [];
        foreach ($questions as $question) {
            if ($question->validation_rules) {
                $ruleKey = "answers.{$question->name}.required";
                $label = strtoupper(substr($question->label, 0, 1)).strtolower(substr($question->label, 1));
                $messages[$ruleKey] = "{$label} is required.";
            }
        }

        return $messages;
    }
}
