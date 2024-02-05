<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\Submission;
use App\Services\OutcomeService;
use Livewire\Component;

class ResultsPage extends Component
{
    protected $outcome;

    protected $form;

    protected $submission;

    protected $answers;

    protected $outcomeText;

    public function mount(OutcomeService $outcomeService, $formName)
    {
        $form = Form::where('name', 'like', str_replace('-', ' ', $formName))->firstOrFail();
        // get submission by user_ip
        try {
            $this->submission = Submission::where('user_ip', request()->ip())
                ->where('form_id', $form->id)
                ->with('outcome')
                ->latest()
                ->firstOrFail();
        } catch (\Exception $e) {
            // remove redirect for testing purposes
            // return redirect("trials/$formName");
        }

        $this->outcome = $this->submission->outcome;
        $this->outcomeText = $outcomeService->getOutcomeText($this->outcome, json_decode($this->submission->answers, true));
        $this->form = $this->submission->form;
        $this->answers = json_decode($this->submission->answers, true);
    }

    public function render()
    {
        return view('livewire.results-page')
            ->extends('layouts.app');
    }
}
