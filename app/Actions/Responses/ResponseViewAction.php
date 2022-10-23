<?php

declare(strict_types=1);

namespace App\Actions\Responses;

class ResponseViewAction
{
    public function __construct(private string $view, private array $viewArgs)
    {
    }

    public function execute(): \Illuminate\View\View
    {
        return view($this->view, $this->viewArgs);
    }
}
