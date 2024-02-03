<?php

namespace App\Livewire;

use App\Models\Form;
use Livewire\Component;

class QuestionnaireForm  extends Component
{
    public $form; // Assuming this is loaded with the form details including questions
    public $answers = [];

    public function mount()
    {
        $this->form = Form::where('name', 'Migraine Trial Questionnaire')->with('questions')->firstOrFail();
        //$this->form = Form::with('questions')->findOrFail($formId);

        foreach ($this->form->questions as $question) {
            $this->answers[$question->id] = '';
        }
    }

    public function submitForm()
    {
        $rules = $this->buildValidationRules();
        $messages = $this->buildValidationMessages();
        $this->validate($rules, $messages);
    }

    protected function buildValidationRules()
    {
        $rules = [];
        foreach ($this->form->questions as $question) {
            if ($question->validation_rules) {
                $rules["answers.{$question->id}"] = $question->validation_rules;
            }
        }

        return $rules;
    }

    protected function buildValidationMessages()
    {
        $messages = [];
        foreach ($this->form->questions as $question) {
            if ($question->validation_rules) {
                $ruleKey = "answers.{$question->id}.required";
                $label = strtoupper(substr($question->label, 0, 1)) . strtolower(substr($question->label, 1));
                $messages[$ruleKey] = "{$label} is required.";
            }
        }
        return $messages;
    }

    public function render()
    {
        return view('livewire.questionnaire-form')
            ->layout('layouts.app');
    }
}

