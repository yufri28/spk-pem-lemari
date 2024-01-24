<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'kriteria';
require_once './header.php';
require_once './functions/kriteria.php';
require_once './functions/hasil.php';

$c1 = 0;
$c2 = 0;
$c3 = 0;
$c4 = 0;
$C1_ = 0;
$C2_ = 0;
$C3_ = 0;
$C4_ = 0;
$total_bobot = 0;
$merek = "Semua";
$post = false;
function merek($koneksi)
{
    $data = $koneksi->query('SELECT merek FROM alternatif');

    $dataMerek = array();
    while ($row = $data->fetch_assoc()) {
        array_push($dataMerek, $row);
    }
    // Hapus duplikat menggunakan fungsi array_unique
    $uniqueRows = array_map("unserialize", array_unique(array_map("serialize", $dataMerek)));

    return $uniqueRows;
}


$dataMerek = merek($koneksi);
// if(isset($_POST['t_bobot_kriteria'])){
//     $C1_ = htmlspecialchars($_POST['t_bobot_kriteria'][0]);
//     $C2_ = htmlspecialchars($_POST['t_bobot_kriteria'][1]);
//     $C3_ = htmlspecialchars($_POST['t_bobot_kriteria'][2]);
//     $C4_ = htmlspecialchars($_POST['t_bobot_kriteria'][3]);
//     $total_bobot = $C1_ + $C2_ + $C3_ + $C4_;
   
//     $c1 = $C1_/$total_bobot;
//     $c2 = $C2_/$total_bobot;
//     $c3 = $C3_/$total_bobot;
//     $c4 = $C4_/$total_bobot;


//     echo $c1;
//     echo $c2;
//     echo $c3;
//     echo $c4;
//     die;
//     $dataBobotKriteria = [$c1,$c2,$c3,$c4];
//     $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
//     $post = true;
// }else{
//     $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
// }        
if(isset($_POST['e_bobot_kriteria'])){
    $C1_ = htmlspecialchars($_POST['e_bobot_kriteria'][0]);
    $C2_ = htmlspecialchars($_POST['e_bobot_kriteria'][1]);
    $C3_ = htmlspecialchars($_POST['e_bobot_kriteria'][2]);
    $C4_ = htmlspecialchars($_POST['e_bobot_kriteria'][3]);
    $merek = htmlspecialchars($_POST['merek']);

    if($C1_ == 0 && $C2_ == 0 && $C3_ == 0 && $C4_ == 0){
        echo "<script>alert('Wajib mengisi bobot kriteria , salah satu bobot kriteria harus memiliki nilai yang lebih besar atau sama dengan 1.');window.location.href='pemilihan_lemari.php'</script>";
    }
    $total_bobot = $C1_ + $C2_ + $C3_ + $C4_;
   
    $c1 = $C1_/$total_bobot;
    $c2 = $C2_/$total_bobot;
    $c3 = $C3_/$total_bobot;
    $c4 = $C4_/$total_bobot;

    $dataBobotKriteria = [
        $c1,$c2,$c3,$c4
    ];
   
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4,$merek);
    $dataPreferensiLimOne = $getDataHasil->getDataPreferensiLimOne($c1,$c2,$c3,$c4,$merek);
    $simpanRiwayat = $getDataHasil->simpanRiwayat($dataPreferensiLimOne['id_alternatif'],$c1,$c2,$c3,$c4,$dataPreferensiLimOne['preferensi']);

    $post = true;
}else{
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4,$merek);
}

?>

<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    title: 'Sukses!',
    text: '<?php echo $_SESSION['success']; ?>',
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['success']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    title: 'Error!',
    text: '<?php echo $_SESSION['error']; ?>',
    icon: 'error',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['error']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let button_like_link = document.getElementById('btn-like-link');

    button_like_link.addEventListener('click', function() {
        Swal.fire({
            title: 'Panduan',
            text: 'Masukan Range Bobot Kriteria Dimana Range Bobot Setiap Kriteria Adalah 0 Sampai 100 dan Bobot Terbesar Menunjukan Kriteria Yang Diprioritaskan.',
            icon: 'warning',
            confirmButtonText: 'Paham'
        });
    });
});
</script>

<style>
.button-like-link {
    background: none;
    border: none;
    color: blue;
    /* Warna teks mirip tautan */
    text-decoration: none;
    /* Garis bawah mirip tautan */
    cursor: pointer;
    /* Jika ingin menyesuaikan tampilan saat digerakkan mouse */
}

.button-like-link:hover {
    text-decoration: none;
    /* Menghilangkan garis bawah saat digerakkan mouse */
    /* Sesuaikan tampilan hover sesuai keinginan */
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="container col-lg-12 mb-5" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <?php if($post == true):?>
                <div class="card-header bg-primary">
                    <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                        Masukkan Bobot Kriteria
                    </h5>
                </div>
                <form method="post" id="editKriteriaForm" action="">
                    <div class="card-body">
                        <div id="bobot-anda" style="color: red; display: none;">
                            Bobot Anda : 100.
                        </div>
                        <div id="error-message" style="color: red; display: none;">
                            Total bobot kriteria harus sama dengan 100.
                        </div>
                        <button type="button" id="btn-like-link"
                            class="button-like-link col-lg-12 d-flex justify-content-end"><small
                                class="">Panduan?</small></button>
                        <script>
                        function updateWeight1(value) {
                            document.getElementById('bobotValue1').innerText = 'Bobot Harga: ' +
                                value;
                        }

                        function updateWeight2(value) {
                            document.getElementById('bobotValue2').textContent = 'Bobot Kualitas: ' + value;
                        }

                        function updateWeight3(value) {
                            document.getElementById('bobotValue3').textContent =
                                'Bobot Volume: ' + value;
                        }

                        function updateWeight4(value) {
                            document.getElementById('bobotValue4').textContent =
                                'Bobot Kelengkapan: ' + value;
                        }


                        // Inisialisasi bobot saat halaman dimuat
                        window.onload = function() {
                            var initialValue1 = document.querySelector('.edit-bobot-kriteria1').value;
                            var initialValue2 = document.querySelector('.edit-bobot-kriteria2').value;
                            var initialValue3 = document.querySelector('.edit-bobot-kriteria3').value;
                            var initialValue4 = document.querySelector('.edit-bobot-kriteria4').value;
                            updateWeight1(initialValue1);
                            updateWeight2(initialValue2);
                            updateWeight3(initialValue3);
                            updateWeight4(initialValue4);
                        };
                        </script>
                        <hr>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Merek</label>
                            <select class="form-control" name="merek" aria-label="Default select example">
                                <option <?= $merek == "Semua" ? 'selected' : '' ?> value="Semua">Semua</option>
                                <?php foreach ($dataMerek as $merek_) : ?>
                                    <option <?= $merek == $merek_['merek'] ? 'selected' : '' ?> value="<?= $merek_['merek']; ?>"><?= $merek_['merek']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <i><small>Range bobot setiap Kriteria : 0 - 100</small></i>
                        <div class="mb-3 mt-3">

                            <span id="bobotValue1"><label for="bobot_kriteria" class="form-label">Bobot Harga</label>:
                                0</span>
                            <input type="range" min="0" max="100" onload="updateWeight1(this.value)"
                                oninput="updateWeight1(this.value)" class="form-control-range edit-bobot-kriteria1"
                                name="e_bobot_kriteria[]" value="<?=$C1_;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="bobotValue2">
                                <<label for="bobot_kriteria" class="form-label">Bobot Kualitas</label>:
                                    0
                            </span>
                            <input type="range" min="0" max="100" onload="updateWeight1(this.value)"
                                oninput="updateWeight2(this.value)" class="form-control-range edit-bobot-kriteria2"
                                name="e_bobot_kriteria[]" value="<?=$C2_;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="bobotValue3"><label for="bobot_kriteria" class="form-label">Bobot Volume</label>:
                                0</span>
                            <input type="range" min="0" max="100" onload="updateWeight1(this.value)"
                                oninput="updateWeight3(this.value)" class="form-control-range edit-bobot-kriteria3"
                                name="e_bobot_kriteria[]" value="<?=$C3_;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="bobotValue4"><label for="bobot_kriteria" class="form-label">Bobot
                                    Kelengkapan</label>:
                                0</span>
                            <input type="range" min="0" max="100" onload="updateWeight1(this.value)"
                                oninput="updateWeight4(this.value)" class="form-control-range edit-bobot-kriteria4"
                                name="e_bobot_kriteria[]" value="<?=$C4_;?>">
                        </div>
                        <button type="submit" name="edit" class="btn col-12 btn-outline-primary">
                            Simpan
                        </button>
                    </div>
                </form>
                <?php endif;?>
                <?php if($post == false):?>
                <div class="card-header bg-primary">
                    <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                        Masukan Bobot Kriteria
                    </h5>
                </div>
                <form method="post" action="">
                    <div class="card-body">
                        <div id="bobot-anda" style="color: red; display: none;">
                            Bobot Anda : 100.
                        </div>
                        <div id="error-message" style="color: red; display: none;">
                            Total bobot kriteria harus sama dengan 100.
                        </div>
                        <button type="button" id="btn-like-link"
                            class="button-like-link col-lg-12 d-flex justify-content-end"><small
                                class="">Panduan?</small></button>
                        <script>
                        function updateWeight1(value) {
                            document.getElementById('weightDisplay1').textContent = 'Bobot Harga: ' +
                                value;
                        }

                        function updateWeight2(value) {
                            document.getElementById('weightDisplay2').textContent = 'Bobot Kualitas: ' + value;
                        }

                        function updateWeight3(value) {
                            document.getElementById('weightDisplay3').textContent =
                                'Bobot Volume: ' + value;
                        }

                        function updateWeight4(value) {
                            document.getElementById('weightDisplay4').textContent =
                                'Bobot Kelengkapan: ' + value;
                        }
                        </script>
                        <hr>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Merek</label>
                            <select class="form-control" name="merek" aria-label="Default select example">
                                <option <?= $merek == "Semua" ? 'selected' : '' ?> value="Semua">Semua</option>
                                <?php foreach ($dataMerek as $merek_) : ?>
                                    <option <?= $merek == $merek_['merek'] ? 'selected' : '' ?> value="<?= $merek_['merek']; ?>"><?= $merek_['merek']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <i><small>Range bobot setiap Kriteria : 0 - 100</small></i>
                        <div class="mb-3 mt-3">
                            <span id="weightDisplay1"><label for="bobot_kriteria" class="form-label">Bobot
                                    Harga</label>: 0</span>
                            <input type="range" min="0" max="100" oninput="updateWeight1(this.value)"
                                class="form-control-range bobot-kriteria" name="e_bobot_kriteria[]" value="<?=$c1;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="weightDisplay2"><label for="bobot_kriteria" class="form-label">Bobot
                                    Kualitas</label>: 0</span>
                            <input type="range" min="0" max="100" oninput="updateWeight2(this.value)"
                                class="form-control-range bobot-kriteria" name="e_bobot_kriteria[]" value="<?=$c2;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="weightDisplay3"><label for="bobot_kriteria" class="form-label">Bobot
                                    Volume</label>: 0</span>
                            <input type="range" min="0" max="100" oninput="updateWeight3(this.value)"
                                class="form-control-range bobot-kriteria" name="e_bobot_kriteria[]" value="<?=$c3;?>">
                        </div>
                        <div class="mb-3 mt-3">

                            <span id="weightDisplay4"><label for="bobot_kriteria" class="form-label">Bobot
                                    Kelengkapan</label>: 0</span>
                            <input type="range" min="0" max="100" oninput="updateWeight4(this.value)"
                                class="form-control-range bobot-kriteria" name="e_bobot_kriteria[]" value="<?=$c4;?>">
                        </div>
                        <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                            Simpan
                        </button>
                    </div>
                </form>
                <?php endif;?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">Hasil Perengkingan</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if(isset($_POST['e_bobot_kriteria'])):?>
                        <table class="table table-bordered nowrap" id="dataLemari" style="width:100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lemari</th>
                                    <th>Gambar</th>
                                    <th>Design</th>
                                    <th>Harga</th>
                                    <th>Kualitas</th>
                                    <th>Volume</th>
                                    <th>Kelengkapan</th>
                                    <th>Merek</th>
                                    <th>Preferensi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataPreferensi as $key => $preferensi):?>
                                <tr>
                                    <td><?=$key+1?></td>
                                    <td><?=$preferensi['nama_alternatif'];?></td>
                                    <td><a href="../images/<?=$preferensi['gambar'];?>" data-lightbox="image-1"
                                            data-title="<?=$preferensi['nama_alternatif'];?>">
                                            <img style="width: 50px; height: 50px;"
                                                src="../images/<?=$preferensi['gambar'];?>"
                                                alt="Gambar <?=$preferensi['nama_alternatif'];?>">
                                        </a></td>
                                    <td><?=$preferensi['design'];?></td>
                                    <td><?=$preferensi['nama_C1'];?></td>
                                    <td><?=$preferensi['nama_C2'];?></td>
                                    <td><?=$preferensi['nama_C3'];?></td>
                                    <td><?=$preferensi['nama_C4'];?></td>
                                    <td><?= $preferensi['merek'] != NULL ? $preferensi['merek']:'-';?></td>
                                    <td><?=$preferensi['preferensi'] != 0 ? $preferensi['preferensi']:0;?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                        <?php else:?>
                        <h5 class="text-center mt-5">Belum ada hasil perengkingan.</h5>
                        <p class="text-center mb-5">Silahkan masukan bobot kriteria terlebih dahulu.</p>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
require_once './footer.php';
?>

<script>
$(document).ready(function() {
    $("#prioritas_1").change(function() {
        var prioritas_1 = $("#prioritas_1").val();
        $.ajax({
            type: 'POST',
            url: "./functions/pilihan.php",
            data: {
                prioritas_1: [prioritas_1]
            },
            cache: false,
            success: function(msg) {
                $("#prioritas_2").html(msg);
            }
        });
    });

    $("#prioritas_2").change(function() {
        var prioritas_1 = $("#prioritas_1").val();
        var prioritas_2 = $("#prioritas_2").val();
        $.ajax({
            type: 'POST',
            url: "./functions/pilihan.php",
            data: {
                prioritas_2: [prioritas_1, prioritas_2]
            },
            cache: false,
            success: function(msg) {
                $("#prioritas_3").html(msg);
            }
        });
    });

    $("#prioritas_3").change(function() {
        var prioritas_1 = $("#prioritas_1").val();
        var prioritas_2 = $("#prioritas_2").val();
        var prioritas_3 = $("#prioritas_3").val();
        $.ajax({
            type: 'POST',
            url: "./functions/pilihan.php",
            data: {
                prioritas_3: [prioritas_1, prioritas_2, prioritas_3]
            },
            cache: false,
            success: function(msg) {
                $("#prioritas_4").html(msg);
            }
        });
    });
});
</script>