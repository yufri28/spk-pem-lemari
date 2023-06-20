<?php
require_once 'config.php';

// Mengambil URL yang dikirimkan melalui aturan rewriting
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Mengonversi URL menjadi array dengan memisahkan setiap segment
$urlSegments = explode('/', rtrim($url, '/'));

// Menentukan halaman yang akan ditampilkan berdasarkan URL
if (empty($urlSegments[0])) {
    // Jika URL kosong, tampilkan halaman beranda
    require_once 'home.php';
} elseif ($urlSegments[0] === 'admin') {
    // Jika URL dimulai dengan "admin", arahkan ke halaman admin
    require_once 'admin/' . $urlSegments[1] . '.php';
} elseif ($urlSegments[0] === 'user') {
    // Jika URL dimulai dengan "user", arahkan ke halaman pengguna biasa
    require_once 'user/views/' . $urlSegments[1];
} else {
    // Jika URL tidak cocok dengan kondisi di atas, tampilkan halaman 404
    require_once '404.php';
}