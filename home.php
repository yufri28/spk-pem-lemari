<?php 
session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 1){
    header("Location: ./user/index.php");
}else if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 0) {
    header("Location: ./admin/index.php");
}
require_once './config.php';
$alternatif = $koneksi->query("SELECT a.nama_alternatif, a.id_alternatif, a.gambar, kak.id_alt_kriteria,
MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN kak.id_alt_kriteria END) AS id_alt_C1,
MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN kak.id_alt_kriteria END) AS id_alt_C2,
MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN kak.id_alt_kriteria END) AS id_alt_C3,
MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN kak.id_alt_kriteria END) AS id_alt_C4,
MAX(CASE WHEN k.nama_kriteria = 'Merek' THEN kak.id_alt_kriteria END) AS id_alt_C5,
MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN kak.f_id_sub_kriteria END) AS id_sub_C1,
MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN kak.f_id_sub_kriteria END) AS id_sub_C2,
MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN kak.f_id_sub_kriteria END) AS id_sub_C3,
MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN kak.f_id_sub_kriteria END) AS id_sub_C4,
MAX(CASE WHEN k.nama_kriteria = 'Merek' THEN kak.f_id_sub_kriteria END) AS id_sub_C5,
MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.nama_sub_kriteria END) AS nama_C1,
MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.nama_sub_kriteria END) AS nama_C2,
MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.nama_sub_kriteria END) AS nama_C3,
MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.nama_sub_kriteria END) AS nama_C4,
MAX(CASE WHEN k.nama_kriteria = 'Merek' THEN sk.nama_sub_kriteria END) AS nama_C5
FROM alternatif a
JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
GROUP BY a.nama_alternatif ORDER BY a.id_alternatif DESC;");

?>

<!DOCTYPE html>
<html>

<head>
    <title>SPK Pemilihan Lemari</title>
    <style>
    #mapid {
        height: 100vh;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Prompt&family=Righteous&family=Roboto:wght@500&display=swap"
        rel="stylesheet" />
</head>

<body>
    <div class="container">
        <div class="logo-section p-2 d-flex">
            <h3 class="mt-2" style="font-family: 'Righteous', cursive">SPK PEMILIHAN KOST</h3>
            <div class="navbar-nav ms-auto">
                <a class="btn btn-primary mt-2 px-4 py-1 btn-sm" href="./auth/login.php">Login</a>
            </div>
        </div>
    </div>
    <div id="mapid">Hallo</div>
    <footer class="bg-white text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #F0F0F0;">
            © 2023 Copyright:
            <a class="text-dark" href="https://www.instagram.com/ilkom19_unc/">Intel'19</a>
        </div>
        <!-- Copyright -->
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>