<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Icon extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $name)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $path = resource_path("svg/{$this->name}.svg");

        return function (array $data) use ($path) {
            if (! file_exists($path)) {
                return "<!-- Icon {$this->name} not found -->";
            }

            $svg = file_get_contents($path);

            // $data['attributes'] is a ComponentAttributeBag when using a closure render
            $attributes = $data['attributes']->merge(['class' => ''])->toHtml();

            // Inject attributes into the <svg ...> tag (only first occurrence)
            $svg = preg_replace(
                '/<svg\b([^>]*)>/i',
                "<svg$1 {$attributes}>",
                $svg,
                1
            );

            return $svg;
        };
    }
}
