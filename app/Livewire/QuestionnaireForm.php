<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\Submission;
use App\Services\OutcomeService;
use App\Services\QuestionService;
use Livewire\Component;

class QuestionnaireForm extends Component
{

    public $form;

    public $answers = [];

    protected QuestionService $questionService;

    public function mount($formName)
    {
        // convert the form name to a route
        $route = strtolower(str_replace('-', ' ', $formName));
        $this->form = Form::where('name', 'like', $route)->with('questions')->firstOrFail();
        $submission = Submission::where('user_ip', request()->ip())
            ->where('form_id', $this->form->id)
            ->first();
        if ($submission) {
            // remove redirect for testing purposes
            // $this->redirect("trials/$formName/results");
        }

        foreach ($this->form->questions as $question) {
            $this->answers[$question->name] = '';
        }
    }

    public function submitForm(OutcomeService $outcomeService)
    {
        $rules = $this->questionService->buildValidationRules($this->form->questions, $this->answers);
        $messages = $this->questionService->buildValidationMessages($this->form->questions);

        $this->validate($rules, $messages);
        $outcome = $outcomeService->getOutcome($this->form, $this->answers);
        $this->form->submissions()->create([
            'outcome_id' => $outcome->id,
            'answers' => json_encode($this->answers),
            'user_ip' => request()->ip(),
        ]);

        $route = strtolower(str_replace(' ', '-', $this->form->name));
        return redirect()->to("trials/$route/results");
    }

    public function isVisible($question)
    {
        return $this->questionService->isVisible($question, $this->answers);
    }

    public function render()
    {
        return view('livewire.questionnaire-form')
            ->extends('layouts.app');
    }

    public function boot(QuestionService $questionService) {
        $this->questionService = $questionService;
    }
}

