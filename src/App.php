<?php

namespace HexagonDev\HiraKataLearner;

class App
{
    private array $katakana;
    private array $hiragana;

    public function __construct()
    {
        $this->katakana = json_decode(file_get_contents(__DIR__ . '/alphabets/katakana.json'), true);
        $this->hiragana = json_decode(file_get_contents(__DIR__ . '/alphabets/hiragana.json'), true);

        $this->katakana = array_reduce(array_keys($this->katakana), function($carry, $key) {
            $carry[] = [
                'character' => $key,
                'romaji' => $this->katakana[$key],
            ];
            return $carry;
        }, []);
        $this->hiragana = array_reduce(array_keys($this->hiragana), function($carry, $key) {
            $carry[] = [
                'character' => $key,
                'romaji' => $this->hiragana[$key],
            ];
            return $carry;
        }, []);
    }

    public function handle(array $get, array $post, array $cookie, array $files, array $server): string
    {
        $response = match(substr($server['REQUEST_URI'], 1)) {
            '' => new Response(Template::render('index')),
            'hiragana' => (function() {
                $guesser = new Guesser($this->hiragana);

                $data = $guesser->generate(rand(0, sizeof($this->hiragana) - 1));

                $template = Template::render('game', [
                    ...$data,
                    'name' => 'hiragana',
                    'description' => 'Hiragana is one of the two basic syllabaries of the Japanese writing system, used primarily for native Japanese words and grammatical elements.',
                ]);

                return new Response($template);
            })(),
            'katakana' => (function() {
                $guesser = new Guesser($this->katakana);

                $data = $guesser->generate(rand(0, sizeof($this->katakana) - 1));

                $template = Template::render('game', [
                    ...$data,
                    'name' => 'katakana',
                    'description' => 'Katakana is one of the two basic syllabaries of the Japanese writing system, primarily used for foreign words, names and technical or scientific terms.',
                ]);

                return new Response($template);
            })(),
            'mix' => (function() {
                $alphabet = [...$this->katakana, ...$this->hiragana];
                $guesser = new Guesser($alphabet);

                $data = $guesser->generate(rand(0, sizeof($alphabet) - 1));

                $template = Template::render('game', [
                    ...$data,
                    'name' => 'mix',
                    'description' => 'Train both Hiragana and Katakana characters in a single game. This mode randomly selects characters from both syllabaries, allowing you to practice recognition and recall of both writing systems simultaneously.',
                ]);

                return new Response($template);
            })(),
            default => new Response(Template::render('404'), 404),
        };

        http_response_code($response->statusCode);
        header("Content-Type: text/html");

        return $response->content;
    }

    public function terminate(): void
    {
        // Perform any cleanup after handling the request
    }

    public function shutdown(): void
    {
        // Perform any final cleanup before the application exits
    }
}