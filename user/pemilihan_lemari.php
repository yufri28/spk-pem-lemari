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

$post = false;
if(isset($_POST['t_bobot_kriteria'])){
    $c1 = htmlspecialchars($_POST['t_bobot_kriteria'][0])/100;
    $c2 = htmlspecialchars($_POST['t_bobot_kriteria'][1])/100;
    $c3 = htmlspecialchars($_POST['t_bobot_kriteria'][2])/100;
    $c4 = htmlspecialchars($_POST['t_bobot_kriteria'][3])/100;
   
    $dataBobotKriteria = [$c1,$c2,$c3,$c4];
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
    $post = true;
}else{
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
}
if(isset($_POST['e_bobot_kriteria'])){

    $c1 = htmlspecialchars($_POST['e_bobot_kriteria'][0])/100;
    $c2 = htmlspecialchars($_POST['e_bobot_kriteria'][1])/100;
    $c3 = htmlspecialchars($_POST['e_bobot_kriteria'][2])/100;
    $c4 = htmlspecialchars($_POST['e_bobot_kriteria'][3])/100;
   
    $dataBobotKriteria = [
        $c1,$c2,$c3,$c4
    ];
   
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
    $post = true;
}else{
    $dataPreferensi = $getDataHasil->getDataPreferensi($c1,$c2,$c3,$c4);
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
            text: 'Masukan Bobot Kriteria Dimana Jumlah Keempat Bobot Adalah 100 dan Bobot Terbesar Menunjukan Kriteria Yang Diprioritaskan.',
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
                        Edit Bobot Kriteria
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
                        <!-- <script>
                        function validateTotal() {
                            let inputs = document.getElementsByClassName('edit-bobot-kriteria');
                            let total = 0;

                            for (let i = 0; i < inputs.length; i++) {
                                total += parseInt(inputs[i].value);
                            }
                            if (total !== 100) {
                                if (total > 100) {
                                    document.getElementById('error-message').innerText =
                                        'Total bobot kriteria tidak boleh melebihi 100.';
                                } else {
                                    document.getElementById('error-message').innerText =
                                        'Total bobot kriteria tidak boleh kurang dari 100.';
                                }

                                document.getElementById('error-message').style.display = 'block';
                                return false; // Menghentikan proses submit jika total tidak sama dengan 100 atau melebihi 100
                            } else {
                                document.getElementById('error-message').style.display = 'none';
                                document.getElementById('editKriteriaForm')
                                    .submit(); // Lakukan pengiriman data form jika validasi berhasil
                            }
                        }
                        </script> -->

                        <script>
                        $(document).ready(function() {
                            let inputs = $('.edit-bobot-kriteria');
                            let sisaBobot = 0;

                            inputs.on('input', function() {
                                let total = 0;
                                inputs.each(function() {
                                    let nilaiInput = parseInt($(this).val()) ||
                                        0; // Pastikan nilai diambil sebagai integer, jika tidak maka gunakan 0
                                    total += nilaiInput;
                                });

                                sisaBobot = 100 - total;
                                $('#error-message').text('Sisa Bobot : ' + sisaBobot);
                                $('#bobot-anda').text('Bobot Anda : ' + total);
                                if (sisaBobot != 0) {
                                    $('#error-message').css('display', 'block');
                                    $('#bobot-anda').css('display', 'block');
                                } else {
                                    $('#error-message').css('display', 'none');
                                    $('#bobot-anda').css('display', 'none');
                                }
                            });
                        });

                        function validateTotal() {
                            let inputs = $('.edit-bobot-kriteria');
                            let total = 0;

                            inputs.each(function() {
                                total += parseInt($(this).val());
                            });

                            if (total !== 100) {
                                if (total > 100) {
                                    $('#error-message').text('Total bobot kriteria tidak boleh melebihi 100.');
                                } else {
                                    $('#error-message').text('Total bobot kriteria tidak boleh kurang dari 100.');
                                }

                                $('#error-message').css('display', 'block');
                                return false;
                            } else {
                                $('#error-message').css('display', 'none');
                                $('#editKriteriaForm').submit();
                            }
                        }
                        </script>

                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Harga</label>
                            <input type="number" max="100" class="form-control edit-bobot-kriteria"
                                name="e_bobot_kriteria[]" value="<?=$c1*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Kualitas</label>
                            <input type="number" max="100" class="form-control edit-bobot-kriteria"
                                name="e_bobot_kriteria[]" value="<?=$c2*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Volume</label>
                            <input type="number" max="100" class="form-control edit-bobot-kriteria"
                                name="e_bobot_kriteria[]" value="<?=$c3*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Kelengkapan</label>
                            <input type="number" max="100" class="form-control edit-bobot-kriteria"
                                name="e_bobot_kriteria[]" value="<?=$c4*100;?>">
                        </div>
                        <button type="button" name="edit" onclick="validateTotal()"
                            class="btn col-12 btn-outline-primary">
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
                <form method="post" id="kriteriaForm" action="">
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
                        $(document).ready(function() {
                            let inputs = $('.bobot-kriteria');
                            let sisaBobot = 0;

                            inputs.on('input', function() {
                                let total = 0;
                                inputs.each(function() {
                                    let nilaiInput = parseInt($(this).val()) ||
                                        0; // Pastikan nilai diambil sebagai integer, jika tidak maka gunakan 0
                                    total += nilaiInput;
                                });

                                sisaBobot = 100 - total;
                                $('#error-message').text('Sisa Bobot : ' + sisaBobot);
                                $('#bobot-anda').text('Bobot Anda : ' + total);
                                if (sisaBobot != 0) {
                                    $('#error-message').css('display', 'block');
                                    $('#bobot-anda').css('display', 'block');
                                } else {
                                    $('#error-message').css('display', 'none');
                                    $('#bobot-anda').css('display', 'none');
                                }
                            });
                        });


                        function validateTotal() {
                            let inputs = $('.bobot-kriteria');
                            let total = 0;
                            inputs.each(function() {
                                total += parseInt($(this).val());
                            });

                            if (total !== 100) {
                                if (total > 100) {
                                    $('#error-message').text('Total bobot kriteria tidak boleh melebihi 100.');
                                } else {
                                    $('#error-message').text(
                                        'Total bobot kriteria tidak boleh kurang dari 100.');
                                }

                                $('#error-message').css('display', 'block');
                                return false;
                            } else {
                                $('#error-message').css('display', 'none');
                                $('#kriteriaForm').submit();
                            }
                        }
                        </script>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Harga</label>
                            <input type="number" max="100" class="form-control bobot-kriteria" name="e_bobot_kriteria[]"
                                value="<?=$c1*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Kualitas</label>
                            <input type="number" max="100" class="form-control bobot-kriteria" name="e_bobot_kriteria[]"
                                value="<?=$c2*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Volume</label>
                            <input type="number" max="100" class="form-control bobot-kriteria" name="e_bobot_kriteria[]"
                                value="<?=$c3*100;?>">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Kelengkapan</label>
                            <input type="number" max="100" class="form-control bobot-kriteria" name="e_bobot_kriteria[]"
                                value="<?=$c4*100;?>">
                        </div>
                        <button type="button" name="simpan" onclick="validateTotal()"
                            class="btn col-12 btn-outline-primary">
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