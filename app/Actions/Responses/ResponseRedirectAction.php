<?php

declare(strict_types=1);

namespace App\Actions\Responses;

class ResponseRedirectAction
{
    public function __construct(private ?string $route = null, private ?string $success = null)
    {
    }

    public function execute(
        ?string $to = null,
        ?int $status = 302,
        array $headers = [],
        ?bool $secure = null
    ): \Illuminate\Http\RedirectResponse {
        $redirect = isset($this->route)
            ? redirect()->route($this->route)
            : redirect($to, $status, $headers, $secure);

        return is_null($this->success)
            ? $redirect
            : $redirect->with('status', $this->success);
    }
}
