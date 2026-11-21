<?php

namespace FlipPlay;

class MediaLibrary
{
    private array $media = [];

    public function addMedia(Media $media): void
    {
        foreach ($this->media as $item) {
            if (strcasecmp($item->getTitle(), $media->getTitle()) === 0) {
                echo "Media dengan judul tersebut sudah tersedia.\n";
                return;
            }
        }

        $media->setId(count($this->media) + 1);
        $this->media[] = $media;
        echo "{$media->getTitle()} berhasil ditambahkan.\n";
    }

    public function showMedia(): void
    {
        echo "\n========== DAFTAR MEDIA ==========\n";

        if (empty($this->media)) {
            echo "Belum ada media tersedia.\n";
            return;
        }

        foreach ($this->media as $item) {
            echo "ID     : {$item->getId()}\n";
            echo "Judul  : {$item->getTitle()}\n";
            echo "Genre  : {$item->getGenre()}\n";
            echo "Rating : {$item->getRating()}\n";
            echo "---------------------------------\n";
        }

        echo "Total Media: " . count($this->media) . "\n";
    }

    public function searchMedia(string $keyword): ?Media
    {
        foreach ($this->media as $item) {
            if (stripos($item->getTitle(), $keyword) !== false) {
                return $item;
            }
        }

        return null;
    }

    public function getMediaById(int $id): ?Media
    {
        foreach ($this->media as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }

    public function updateMedia(int $id, string $title, string $genre, float $rating, int $specificInfo): bool
    {
        $media = $this->getMediaById($id);

        if ($media === null) {
            echo "Media tidak ditemukan.\n";
            return false;
        }

        foreach ($this->media as $item) {
            if ($item->getId() !== $id && strcasecmp($item->getTitle(), $title) === 0) {
                echo "Judul media sudah digunakan.\n";
                return false;
            }
        }

        $media->updateInfo($title, $genre, $rating);

        if ($media instanceof Anime) {
            $media->updateEpisodes($specificInfo);
        } elseif ($media instanceof Movie) {
            $media->updateDuration($specificInfo);
        }

        echo "Informasi media berhasil diperbarui.\n";
        return true;
    }

    public function deleteMedia(int $id): void
    {
        foreach ($this->media as $index => $item) {
            if ($item->getId() === $id) {
                $title = $item->getTitle();
                array_splice($this->media, $index, 1);

                foreach ($this->media as $newIndex => $media) {
                    $media->setId($newIndex + 1);
                }

                echo "{$title} berhasil dihapus.\n";
                return;
            }
        }

        echo "Media tidak ditemukan.\n";
    }
}
