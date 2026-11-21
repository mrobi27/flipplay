<?php

namespace FlipPlay;

class Media
{
    private int $id;
    private string $title;
    private string $genre;
    private float $rating;

    public function __construct(string $title, string $genre, float $rating)
    {
        $this->title = $title;
        $this->genre = $genre;
        $this->rating = $rating;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function updateInfo(string $title, string $genre, float $rating): void
    {
        $this->title = $title;
        $this->genre = $genre;
        $this->rating = $rating;
    }

    public function getDetails(): string
    {
        return "ID: {$this->id}\n"
            . "Judul: {$this->title}\n"
            . "Genre: {$this->genre}\n"
            . "Rating: {$this->rating}";
    }
}
