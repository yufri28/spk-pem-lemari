<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'index';
?>
<?php require './header.php';?>
<div class="row">
    <!-- Area Chart -->
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-4 d-flex flex-row align-items-center justify-content-between">
                <div class="justify-content-center text-center p-5">
                    <h5 class="text-center mb-5">
                        SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LEMARI
                    </h5>
                    <p>
                        Sistem pendukung keputusan dinyatakan pertama kali oleh Michael S.
                        Scott Morton pada tahun 1970 dengan istilah lain “Management
                        Decision System” SPK dibuat untuk mendukung tahap pengambilan
                        keputusan diawali dengan mengidentifikasi masalah, memilih data
                        relevan, menentukan pendekatan yang digunakan dalam proses
                        pengambilan keputusan, sampai mengevaluasi pemilihan alternatif.
                    </p>
                    <p>
                        Toko Virgo Mebel adalah salah satu dari sekian banyak
                        toko/perusahaan yang bergerak dalam bidang mebel, dimana toko ini
                        menjual barang berupa lemari, kursi, meja dll.
                    </p>
                    <p>
                        Toko ini memasarkan produk mulai dari kualitas standar sampai
                        kualitas tinggi.Toko Virgo Mebel berdiri pada tanggal 6 Februari
                        1995 dan berlokasi di Jl.Ketumbar, Kelurahan Kefamenanu Tengah,
                        Kecamatan Kota Kefamenanu, Timor Tengah Utara Nusa Tenggara Timur.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require './footer.php';?>