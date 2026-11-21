<?php

namespace FlipPlay;

class Movie extends Media
{
    private int $duration;

    public function __construct(string $title, string $genre, float $rating, int $duration)
    {
        parent::__construct($title, $genre, $rating);
        $this->duration = $duration;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function updateDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getDetails(): string
    {
        return parent::getDetails()
            . "\nDurasi: {$this->duration} menit";
    }
}
