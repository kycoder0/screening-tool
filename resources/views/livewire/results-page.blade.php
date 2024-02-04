<div class="flex flex-col justify-center items-center w-full">
    <div class="w-full px-5 md:w-4/5 lg:2/3 xl:1/3 mt-28">
        <h1 class="text-indigo-500 text-5xl">{{$this->form->name}}</h1>
        <h2 class="text-indigo-800 text-3xl">Your result</h2>
        <p class="mt-10 text-3xl">
            {{$this->outcomeText}}
        </p>
        <!-- display all answers from form -->
        <div class="mt-10">
            @foreach($this->form->questions as $question)
                @if (!empty($this->answers[$question->name]))
                    <div class="mb-5">
                        <h3 class="text-indigo-800 text-2xl">{{$question->question}}</h3>
                        <p class="mt-2 text-left">
                            <span class="text-indigo-500">{{$question->label}} </span><br/>
                            {{$this->answers[$question->name]}}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
