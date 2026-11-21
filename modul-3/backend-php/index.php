<?php
require_once __DIR__ . '/src/Media.php';
require_once __DIR__ . '/src/Anime.php';
require_once __DIR__ . '/src/Movie.php';
require_once __DIR__ . '/src/MediaLibrary.php';
require_once __DIR__ . '/src/User.php';

use FlipPlay\Anime;
use FlipPlay\Movie;
use FlipPlay\MediaLibrary;
use FlipPlay\User;

$library = new MediaLibrary();
$user = new User("Robi");

$library->addMedia(new Anime("Naruto", "Action", 8.5, 220));
$library->addMedia(new Anime("One Piece", "Adventure", 9.0, 1100));
$library->addMedia(new Anime("Demon Slayer", "Action", 8.7, 55));
$library->addMedia(new Movie("Your Name", "Romance", 8.9, 106));
$library->addMedia(new Movie("Spirited Away", "Fantasy", 8.6, 125));

do {
    echo "\n                 FLIPPLAY\n";
    echo "       SISTEM PERPUSTAKAAN HIBURAN\n";
    echo "========================================\n";
    echo "1. Masuk sebagai Admin\n";
    echo "2. Masuk sebagai User\n";
    echo "3. Keluar\n";
    echo "----------------------------------------\n";

    $choice = readline("Pilih menu: ");

    switch ($choice) {
        case '1':
            do {
                echo "\n========== MENU ADMIN ==========\n";
                echo "1. Tambah Media\n";
                echo "2. Tampilkan Semua Media\n";
                echo "3. Cari Media\n";
                echo "4. Lihat Detail Media\n";
                echo "5. Perbarui Informasi Media\n";
                echo "6. Hapus Media\n";
                echo "7. Kembali ke Menu Utama\n";

                $adminChoice = readline("Pilih menu: ");

                switch ($adminChoice) {
                    case '1':
                        echo "\n--- TAMBAH MEDIA ---\n";
                        echo "1. Anime\n";
                        echo "2. Film\n";

                        $type = readline("Pilih jenis media: ");

                        if ($type !== '1' && $type !== '2') {
                            echo "Jenis media tidak valid.\n";
                            break;
                        }

                        $title = readline("Masukkan judul: ");
                        $genre = readline("Masukkan genre: ");
                        $rating = (float) readline("Masukkan rating: ");

                        if ($type === '1') {
                            $episodes = (int) readline("Jumlah episode: ");
                            $library->addMedia(new Anime($title, $genre, $rating, $episodes));
                        } else {
                            $duration = (int) readline("Durasi film (menit): ");
                            $library->addMedia(new Movie($title, $genre, $rating, $duration));
                        }
                        break;

                    case '2':
                        $library->showMedia();
                        break;

                    case '3':
                        $keyword = readline("Masukkan judul yang dicari: ");
                        $media = $library->searchMedia($keyword);

                        if ($media !== null) {
                            echo "\n" . $media->getDetails() . "\n";
                        } else {
                            echo "Media tidak ditemukan.\n";
                        }
                        break;

                    case '4':
                        $id = (int) readline("Masukkan ID media: ");
                        $media = $library->getMediaById($id);

                        if ($media !== null) {
                            echo "\n========== DETAIL MEDIA ==========\n";
                            echo $media->getDetails() . "\n";
                        } else {
                            echo "Media tidak ditemukan.\n";
                        }
                        break;

                    case '5':
                        $id = (int) readline("Masukkan ID media yang ingin diperbarui: ");
                        $media = $library->getMediaById($id);

                        if ($media === null) {
                            echo "Media tidak ditemukan.\n";
                            break;
                        }

                        echo "\n========== DATA MEDIA ==========\n";
                        echo $media->getDetails() . "\n";

                        $title = readline("\nMasukkan judul baru: ");
                        $genre = readline("Masukkan genre baru: ");
                        $rating = (float) readline("Masukkan rating baru: ");

                        if ($media instanceof Anime) {
                            $specificInfo = (int) readline("Jumlah episode baru: ");
                        } else {
                            $specificInfo = (int) readline("Durasi baru (menit): ");
                        }

                        $library->updateMedia($id, $title, $genre, $rating, $specificInfo);
                        break;

                    case '6':
                        $id = (int) readline("Masukkan ID media yang ingin dihapus: ");
                        $library->deleteMedia($id);
                        break;

                    case '7':
                        echo "Kembali ke menu utama.\n";
                        break;

                    default:
                        echo "Pilihan tidak valid.\n";
                }
            } while ($adminChoice !== '7');
            break;

        case '2':
            do {
                echo "\n========== MENU USER ==========\n";
                echo "Pengguna: {$user->getName()}\n";
                echo "1. Tampilkan Semua Media\n";
                echo "2. Cari Media\n";
                echo "3. Lihat Detail Media\n";
                echo "4. Tambahkan ke Koleksi Saya\n";
                echo "5. Tampilkan Koleksi Saya\n";
                echo "6. Kembali ke Menu Utama\n";
                echo "--------------------------------\n";

                $userChoice = readline("Pilih menu: ");

                switch ($userChoice) {
                    case '1':
                        $library->showMedia();
                        break;

                    case '2':
                        $keyword = readline("Masukkan judul yang dicari: ");
                        $media = $library->searchMedia($keyword);

                        if ($media !== null) {
                            echo "\n" . $media->getDetails() . "\n";
                        } else {
                            echo "Media tidak ditemukan.\n";
                        }
                        break;

                    case '3':
                        $id = (int) readline("Masukkan ID media: ");
                        $media = $library->getMediaById($id);

                        if ($media !== null) {
                            echo "\n========== DETAIL MEDIA ==========\n";
                            echo $media->getDetails() . "\n";
                        } else {
                            echo "Media tidak ditemukan.\n";
                        }
                        break;

                    case '4':
                        $id = (int) readline("Masukkan ID media: ");
                        $media = $library->getMediaById($id);

                        if ($media !== null) {
                            $user->addToCollection($media);
                        } else {
                            echo "Media tidak ditemukan.\n";
                        }
                        break;

                    case '5':
                        $user->showCollection();
                        break;

                    case '6':
                        echo "Kembali ke menu utama.\n";
                        break;

                    default:
                        echo "Pilihan tidak valid.\n";
                }
            } while ($userChoice !== '6');
            break;

        case '3':
            echo "\nTerima kasih telah menggunakan FlipPlay!\n";
            break;

        default:
            echo "Pilihan tidak valid.\n";
    }
} while ($choice !== '3');
