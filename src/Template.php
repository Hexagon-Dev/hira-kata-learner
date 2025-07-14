<?php

namespace HexagonDev\HiraKataLearner;

class Template
{
    public static function render(string $name, array $variables = []): string
    {
        $templatePath = __DIR__ . "/templates/$name.html";

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: {$name}");
        }

        $template = file_get_contents($templatePath);

        if ($template === false) {
            throw new \RuntimeException("Failed to read template: {$name}");
        }

        foreach ($variables as $key => $value) {
            $value = is_array($value) ? json_encode($value) : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

            $template = str_replace("{{ $$key }}", $value, $template);
        }

        $baseTemplate = file_get_contents(__DIR__ . '/templates/base.html');

        return str_replace('<div id="app"></div>', $template, $baseTemplate);
    }
}