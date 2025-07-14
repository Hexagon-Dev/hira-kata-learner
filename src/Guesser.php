<?php

namespace HexagonDev\HiraKataLearner;

class Guesser
{
    public function __construct(public array $collection)
    {
    }

    public function generate(int $correct, int $variants = 4): array
    {
        if ($correct > count($this->collection)) {
            throw new \InvalidArgumentException('Correct guess cannot exceed the number of items in the collection.');
        }

        $guesses = [];

        $correct = $this->collection[$correct];
        $guesses[] = $correct;

        $shuffled = $this->collection;
        shuffle($shuffled);

        while (count($guesses) < $variants) {
            $guess = array_pop($shuffled);

            if ($guess === $correct || in_array($guess, $guesses, true)) {
                continue;
            }

            $guesses[] = $guess;
        }

        shuffle($guesses);

        return [
            'guesses' => $guesses,
            'correct' => $correct,
        ];
    }
}