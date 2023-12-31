<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'kriteria';
require_once './header.php';
require_once './functions/kriteria.php';
$id_user = $_SESSION['id_user'];

if(isset($_POST['t_bobot_kriteria'])){
    $c1 = htmlspecialchars($_POST['t_bobot_kriteria'][0])/100;
    $c2 = htmlspecialchars($_POST['t_bobot_kriteria'][1])/100;
    $c3 = htmlspecialchars($_POST['t_bobot_kriteria'][2])/100;
    $c4 = htmlspecialchars($_POST['t_bobot_kriteria'][3])/100;
   
   
    $dataBobotKriteria = [$c1,$c2,$c3,$c4];
    $tambahBobotKriteria = $Kriteria->tambahBobotKriteria($dataBobotKriteria,$id_user);
}
if(isset($_POST['e_bobot_kriteria'])){

    $id_bobot = $_POST['id_bobot'];
    $c1 = htmlspecialchars($_POST['e_bobot_kriteria'][0])/100;
    $c2 = htmlspecialchars($_POST['e_bobot_kriteria'][1])/100;
    $c3 = htmlspecialchars($_POST['e_bobot_kriteria'][2])/100;
    $c4 = htmlspecialchars($_POST['e_bobot_kriteria'][3])/100;
   
    $dataBobotKriteria = [
        $c1,$c2,$c3,$c4
    ];
   
    $Kriteria->editBobotKriteria($id_bobot,$dataBobotKriteria);
}

$data_Kriteria = $Kriteria->getKriteriaByUser($id_user);
$dataBobot = $Kriteria->getBobotKriteria($id_user);
$id_bobot = mysqli_fetch_assoc($data_Kriteria);
$dataKriteria = $Kriteria->getKriteria();

$dataTampung = $koneksi->query("SELECT * FROM tabel_tampung WHERE f_id_user='$id_user'");


?>
<!-- Tampilkan pesan sukses atau error jika sesi tersebut diatur -->
<?php if (mysqli_num_rows($data_Kriteria) <= 0): ?>
<script>
Swal.fire({
    title: 'Panduan',
    text: 'Masukan Bobot Kriteria Dimana Jumlah Keempat Bobot Adalah 100 dan Bobot Terbesar Menunjukan Kriteria Yang Diprioritaskan.',
    icon: 'warning',
    confirmButtonText: 'Paham'
});
</script>
<?php endif; ?>
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
<div class="container" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <!-- <div class="d-md-flex"> -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <?php if(mysqli_num_rows($data_Kriteria) >= 1):?>
                <?php foreach ($dataBobot as $bobot) :?>
                <div class="card-header bg-primary">
                    <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                        Edit Bobot Kriteria
                    </h5>
                </div>
                <form method="post" id="editKriteriaForm" action="">
                    <div class="card-body">
                        <div id="error-message" style="color: red; display: none;">
                            Total bobot kriteria harus sama dengan 100.
                        </div>
                        <button type="button" id="btn-like-link"
                            class="button-like-link col-lg-12 d-flex justify-content-end"><small
                                class="">Panduan?</small></button>
                        <script>
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
                        </script>
                        <?php $i = 1;?>
                        <?php foreach($dataKriteria as $kriteria):?>
                        <div class="mb-3 mt-3">
                            <input type="hidden" name="id_bobot" value="<?=$bobot['id_bobot'];?>">
                            <label for="bobot_kriteria" class="form-label"><?=$kriteria['nama_kriteria'];?></label>
                            <input type="number" max="100" class="form-control edit-bobot-kriteria"
                                name="e_bobot_kriteria[]" value="<?=($bobot['C'.$i++]*100);?>">
                        </div>
                        <?php endforeach;?>
                        <button type="button" name="edit" onclick="validateTotal()"
                            class="btn col-12 btn-outline-primary">
                            Simpan
                        </button>
                    </div>
                </form>
                <?php endforeach;?>
                <?php endif;?>
                <?php if(mysqli_num_rows($data_Kriteria) <= 0):?>
                <div class="card-header bg-primary">
                    <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                        Masukan Bobot Kriteria
                    </h5>
                </div>
                <form method="post" id="kriteriaForm" action="">
                    <div class="card-body">
                        <div id="error-message" style="color: red; display: none;">
                            Total bobot kriteria harus sama dengan 100.
                        </div>
                        <script>
                        function validateTotal() {
                            let inputs = document.getElementsByClassName('bobot-kriteria');
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
                                document.getElementById('kriteriaForm')
                                    .submit(); // Lakukan pengiriman data form jika validasi berhasil
                            }
                        }
                        </script>
                        <?php foreach($dataKriteria as $kriteria):?>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label"><?=$kriteria['nama_kriteria'];?></label>
                            <input type="number" max="100" class="form-control bobot-kriteria" name="t_bobot_kriteria[]"
                                value="0">
                        </div>
                        <?php endforeach;?>
                        <button type="button" name="simpan" onclick="validateTotal()"
                            class="btn col-12 btn-outline-primary">
                            Simpan
                        </button>
                    </div>
                </form>
                <?php endif;?>
            </div>
        </div>
        <div class="col-md-8 mt-5">
            <div class="card">
                <div class="card-header">Daftar Kriteria</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Bobot</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">

                                <?php foreach ($data_Kriteria as $key => $kriteria):?>
                                <tr>
                                    <th scope="row"><?=$key+1;?></th>
                                    <td><?=$kriteria['nama_kriteria'];?></td>
                                    <td><?=$kriteria['C'.$key+1];?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
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