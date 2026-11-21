<?php

namespace FlipPlay;

class Anime extends Media
{
    private int $episodes;

    public function __construct(string $title, string $genre, float $rating, int $episodes)
    {
        parent::__construct($title, $genre, $rating);
        $this->episodes = $episodes;
    }

    public function getEpisodes(): int
    {
        return $this->episodes;
    }

    public function updateEpisodes(int $episodes): void
    {
        $this->episodes = $episodes;
    }

    public function getDetails(): string
    {
        return parent::getDetails()
            . "\nJumlah Episode: {$this->episodes}";
    }
}
