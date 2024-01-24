<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'riwayat';
require_once './header.php';
require_once './functions/data_lemari.php';

$getRiwayat = $Lemari->getRiwayat();

if(isset($_POST['hapus'])){
    $id_riwayat = htmlspecialchars($_POST['id_riwayat']);
    $Lemari->hapusRiwayat($id_riwayat);
}

?>
<?php if (isset($_SESSION['success'])): ?>
<script>
var successfuly = '<?php echo $_SESSION["success"]; ?>';
Swal.fire({
    title: 'Sukses!',
    text: successfuly,
    icon: 'success',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
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
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
});
</script>
<?php unset($_SESSION['error']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<div class="row">
    <!-- Area Chart -->
    <!-- Button trigger modal -->
    <div class="col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                Featured
            </div> -->
            <div class="card-body">
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat</h6>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($getRiwayat as $keys => $Riw){?>
                                    <?php 

                                    $id = $Riw['id_alternatif_terting'];
                                    $c1 = $Riw['c1'];
                                    $c2 = $Riw['c2'];
                                    $c3 = $Riw['c3'];
                                    $c4 = $Riw['c4'];
                                    $dataRiwayat = $Lemari->getDataRiwayat($id,$c1,$c2,$c3,$c4);
                                    ?>
                                    <?php foreach ($dataRiwayat as $key => $riwayat):?>
                                    <tr>
                                        <td><?=$keys+1?></td>
                                        <td><?=$riwayat['nama_alternatif'];?></td>
                                        <td><a href="../images/<?=$riwayat['gambar'];?>" data-lightbox="image-1"
                                                data-title="<?=$riwayat['nama_alternatif'];?>">
                                                <img style="width: 50px; height: 50px;"
                                                    src="../images/<?=$riwayat['gambar'];?>"
                                                    alt="Gambar <?=$riwayat['nama_alternatif'];?>">
                                            </a></td>
                                        <td><?=$riwayat['design'];?></td>
                                        <td><?=$riwayat['nama_C1'];?></td>
                                        <td><?=$riwayat['nama_C2'];?></td>
                                        <td><?=$riwayat['nama_C3'];?></td>
                                        <td><?=$riwayat['nama_C4'];?></td>
                                        <td><?= $riwayat['merek'] != NULL ? $riwayat['merek']:'-';?></td>
                                        <td><?=$riwayat['preferensi'] != 0 ? round($riwayat['preferensi'],3):0;?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                data-target="#hapus<?= $Riw['id_riwayat'];?>">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    <?php };?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php foreach ($getRiwayat as $getRiw):?>
<div class="modal fade" id="hapus<?=$getRiw['id_riwayat'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_riwayat" value="<?=$getRiw['id_riwayat'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus riwayat <strong>
                            <?=$getRiw['id_riwayat'];?></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="hapus" class="btn btn-primary">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php require_once './footer.php';?>