<?php

namespace App\Models;

class ListItem
{
    private string $text;

    public function getText(): string
    {
        return $this->text;
    }

    public function __construct(string $text)
    {
        $this->text = $text;
    }
}
