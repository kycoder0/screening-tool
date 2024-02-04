<?php

namespace App\Livewire;

use App\Models\Outcome;
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
        // get submission by user_ip
        try {
            $this->submission = Submission::where('user_ip', request()->ip())->firstOrFail();
        } catch (\Exception $e) {
            return redirect("trials/$formName");
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
