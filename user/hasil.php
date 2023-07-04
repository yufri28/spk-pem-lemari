<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'hasil';
require_once './header.php';
require_once './functions/hasil.php';
$id_user = $_SESSION['id_user'];
$dataPreferensi = $getDataHasil->getDataPreferensi($id_user);
$selectBobot = $koneksi->query("SELECT * FROM bobot_kriteria WHERE f_id_user='$id_user'");

if(mysqli_num_rows($selectBobot) <= 0){
    $_SESSION['error-bobot'] = 'Harap mengisi data bobot kriteria terlebih dahulu!';
}
?>
<?php if (isset($_SESSION['error-bobot'])): ?>
<script>
var errorBobot = '<?php echo $_SESSION["error-bobot"]; ?>';
Swal.fire({
    title: 'Error!',
    text: errorBobot,
    icon: 'error',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = './kriteria.php';
    }
});
</script>
<?php unset($_SESSION['error-bobot']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<div class="row">
    <!-- Area Chart -->
    <!-- Button trigger modal -->
    <div class="col-lg-12">
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
            + Tambah data
        </button>
        <div class="card">
            <!-- <div class="card-header">
                Featured
            </div> -->
            <div class="card-body">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Hasil Perengkingan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap" id="dataLemari" style="width:100%"
                                cellspacing="0">
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
                                        <td><?=$preferensi['nama_C5'];?></td>
                                        <td><?=$preferensi['preferensi'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php require_once './footer.php';?>