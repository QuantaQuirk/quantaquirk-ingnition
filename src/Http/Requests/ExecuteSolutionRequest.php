<?php

namespace QuantaQuirk\QuantaQuirkIgnition\Http\Requests;

use QuantaQuirk\Foundation\Http\FormRequest;
use QuantaQuirk\Ignition\Contracts\RunnableSolution;
use QuantaQuirk\Ignition\Contracts\Solution;
use QuantaQuirk\Ignition\Contracts\SolutionProviderRepository;

class ExecuteSolutionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'solution' => 'required',
            'parameters' => 'array',
        ];
    }

    public function getSolution(): Solution
    {
        $solution = app(SolutionProviderRepository::class)
            ->getSolutionForClass($this->get('solution'));

        abort_if(is_null($solution), 404, 'Solution could not be found');

        return $solution;
    }

    public function getRunnableSolution(): RunnableSolution
    {
        $solution = $this->getSolution();

        if (! $solution instanceof RunnableSolution) {
            abort(404, 'Runnable solution could not be found');
        }

        return $solution;
    }
}
