<?php

namespace App\Livewire;

use App\Models\Form;
use App\Services\OutcomeService;
use Livewire\Component;

class QuestionnaireForm extends Component
{
    const ALWAYS_VISIBLE_RULE = 'always_visible';

    public $form;

    public $answers = [];

    public function mount($formName)
    {
        // convert the form name to a route
        $route = strtolower(str_replace('-', ' ', $formName));
        $this->form = Form::where('name', 'like', $route)->with('questions')->firstOrFail();
        $submission = Submission::where('user_ip', request()->ip())
            ->where('form_id', $this->form->id)
            ->first();
        if ($submission) {
            $this->redirect("trials/$formName/results");
        }

        foreach ($this->form->questions as $question) {
            $this->answers[$question->name] = '';
        }
    }

    public function submitForm(OutcomeService $outcomeService)
    {
        $rules = $this->buildValidationRules();
        $messages = $this->buildValidationMessages();
        $this->validate($rules, $messages);
        $outcome = $outcomeService->getOutcome($this->form, $this->answers);
        $this->form->submissions()->create([
            'outcome_id' => $outcome->id,
            'answers' => json_encode($this->answers),
            'user_ip' => request()->ip(),
        ]);

        $outcomeText = $outcomeService->getOutcomeText($outcome, $this->answers);
        // TODO save answers and redirect to results page
    }

    protected function buildValidationRules()
    {
        $rules = [];
        foreach ($this->form->questions as $question) {
            if ($question->validation_rules) {
                $rules["answers.{$question->name}"] = $question->validation_rules;
                // if not visible, remove "required" rule from the rules array
                if (!$this->isVisible($question)) {
                    // filter out the "required" rule
                    $rules["answers.{$question->name}"] = array_filter($question->validation_rules, function ($rule) {
                        return $rule != 'required';
                    });
                }
            }
        }

        return $rules;
    }

    protected function buildValidationMessages()
    {
        $messages = [];
        foreach ($this->form->questions as $question) {
            if ($question->validation_rules) {
                $ruleKey = "answers.{$question->name}.required";
                $label = strtoupper(substr($question->label, 0, 1)) . strtolower(substr($question->label, 1));
                $messages[$ruleKey] = "{$label} is required.";
            }
        }
        return $messages;
    }

     public function isVisible($question)
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
        return isset($this->answers[$dependsOnName]) && $this->answers[$dependsOnName] == $requiredValue;
    }

    protected function findQuestionIdByName($name)
    {
        foreach ($this->form->questions as $question) {
            if ($question->name == $name) {
                return $question->id;
            }
        }
        return null;
    }

    public function render()
    {
        return view('livewire.questionnaire-form')
            ->extends('layouts.app');
    }
}

