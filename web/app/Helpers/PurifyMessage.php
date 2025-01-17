<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PurifyMessage
{
    /**
     * Create a new class instance.
     */
    public function __construct(protected string $message){}

    /**
     * this purify method convert the string in the purification form
     *
     * @return string
     */
    public function purify(): string{
        $this->message = Str::trim($this->message);
        $this->message = Str::lower($this->message);
        return $this->message;
    }
}
