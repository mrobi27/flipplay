<?php

namespace FlipPlay;

class User
{
    private string $name;
    private array $collection = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addToCollection(Media $media): void
    {
        foreach ($this->collection as $item) {
            if ($item->getId() === $media->getId()) {
                echo "Media sudah ada di koleksi.\n";
                return;
            }
        }

        $this->collection[] = $media;
        echo "{$media->getTitle()} berhasil ditambahkan ke koleksi.\n";
    }

    public function showCollection(): void
    {
        echo "\n========== KOLEKSI SAYA ==========\n";
        echo "Pengguna: {$this->name}\n";

        if (empty($this->collection)) {
            echo "Koleksi masih kosong.\n";
            return;
        }

        foreach ($this->collection as $index => $item) {
            echo ($index + 1) . ". {$item->getTitle()}\n";
        }

        echo "Total Koleksi: " . count($this->collection) . "\n";
    }
}
