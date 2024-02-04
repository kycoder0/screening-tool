<div class="flex flex-col justify-center items-center h-100 w-full">
    <div class="w-full lg:w-2/3 xl:w-1/2 mt-28 mb-16 px-5">
        <h1 class="text-indigo-500 text-5xl">{{ $form->name }}</h1>
        <p class="text-indigo-950 text-xl mt-2">{{ $form->description }}</p>

        <form wire:submit.prevent="submitForm" class="mt-16 flex flex-col gap-8">
            @foreach ($form->questions as $question)
                @if ($this->isVisible($question))
                    <div class="flex flex-col">
                        <label class="text-lg" for="question_{{ $question->name }}">{{ $question->text }}</label>
                        @if ($question->input_type == 'text')
                            <input class="rounded-md" type="text" wire:model.live.debounce="answers.{{ $question->name }}" id="question_{{ $question->name }}">
                        @elseif ($question->input_type == 'date')
                            <input class="rounded-md" type="date" wire:model.live="answers.{{ $question->name }}" id="question_{{ $question->name }}">
                        @elseif ($question->input_type == 'radio')
                            @foreach ($question->options as $option)
                                <label for="question_{{ $question->id }}">
                                    <input type="radio" name="{{$question->name}}" wire:model.live="answers.{{ $question->name }}" value="{{ $option }}"> {{ $option }}
                                </label>
                            @endforeach
                        @endif
                        @error('answers.'.$question->name) <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                @endif
            @endforeach
            <button class="bg-indigo-500 py-2 w-full rounded-lg text-white" type="submit">Submit</button>
        </form>
    </div>
</div>
