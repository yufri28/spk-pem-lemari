<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'lemari';
require_once './header.php';
require_once './functions/data_lemari.php';
$dataAlternatif = $Lemari->getLemari();
$dataSubHarga = $Lemari->getSubHarga();
$dataSubKualitas = $Lemari->getSubKualitas();
$dataSubVolume = $Lemari->getSubVolume();
$dataSubKelengkapan = $Lemari->getSubKelengkapan();
$dataDesign = ['Motif', 'Warna'];

// tambah alternatif/lemari
if(isset($_POST['tambah'])){
    $nama_alternatif = htmlspecialchars($_POST['nama_alternatif']);
    $design = htmlspecialchars($_POST['design']);
    $merek = htmlspecialchars($_POST['merek']);
    
    // Pastikan ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['gambar']['name'];
        $lokasiSementara = $_FILES['gambar']['tmp_name'];
        
        // Tentukan lokasi tujuan penyimpanan
        $targetDir = '../images/';
        $targetFilePath = $targetDir . $namaFile;

        // Cek apakah nama file sudah ada dalam direktori target
        if (file_exists($targetFilePath)) {
            $fileInfo = pathinfo($namaFile);
            $baseName = $fileInfo['filename'];
            $extension = $fileInfo['extension'];
            $counter = 1;

            // Loop hingga menemukan nama file yang unik
            while (file_exists($targetFilePath)) {
                $namaFile = $baseName . '_' . $counter . '.' . $extension;
                $targetFilePath = $targetDir . $namaFile;
                $counter++;
            }
        }

        // Pindahkan file gambar dari lokasi sementara ke lokasi tujuan
        if (move_uploaded_file($lokasiSementara, $targetFilePath)) {
            $harga = htmlspecialchars($_POST['harga']);
            $kualitas = htmlspecialchars($_POST['kualitas']);
            $volume = htmlspecialchars($_POST['volume']);
            $kelengkapan = htmlspecialchars($_POST['kelengkapan']);
        
            $dataLemari = [
                'nama_alternatif' => $nama_alternatif,
                'gambar' => $namaFile,
                'design' => $design,
                'merek' => $merek
            ];
            
            $dataKecAltKrit = [
                'C1' => $harga,
                'C2' => $kualitas,
                'C3' => $volume,
                'C4' => $kelengkapan
            ];
            $Lemari->addDataLemari($dataLemari,$dataKecAltKrit);
        } else {
            return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
        }
    } else {
        return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
    }    
}

// edit alternatif/lemari
if(isset($_POST['edit'])){
    $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
    $nama_alternatif = htmlspecialchars($_POST['nama_alternatif']);
    $design = htmlspecialchars($_POST['design']);
    $merek = htmlspecialchars($_POST['merek']);
    
    // Pastikan ada file gambar yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['gambar']['name'];
        $lokasiSementara = $_FILES['gambar']['tmp_name'];
        
        // Tentukan lokasi tujuan penyimpanan
        $targetDir = '../images/';
        $targetFilePath = $targetDir . $namaFile;

        // Cek apakah nama file sudah ada dalam direktori target
        if (file_exists($targetFilePath)) {
            $fileInfo = pathinfo($namaFile);
            $baseName = $fileInfo['filename'];
            $extension = $fileInfo['extension'];
            $counter = 1;

            // Loop hingga menemukan nama file yang unik
            while (file_exists($targetFilePath)) {
                $namaFile = $baseName . '_' . $counter . '.' . $extension;
                $targetFilePath = $targetDir . $namaFile;
                $counter++;
            }
        }

        // Pindahkan file gambar dari lokasi sementara ke lokasi tujuan
        if (move_uploaded_file($lokasiSementara, $targetFilePath)) {
            // Hapus file gambar lama jika ada
            $gambarLama = $_POST['gambar_lama'];
            $pathGambarLama = $targetDir . $gambarLama;
            if (file_exists($pathGambarLama) && is_file($pathGambarLama)) {
                unlink($pathGambarLama); // Hapus file gambar lama
            }

            $harga = htmlspecialchars($_POST['harga']);
            $kualitas = htmlspecialchars($_POST['kualitas']);
            $volume = htmlspecialchars($_POST['volume']);
            $kelengkapan = htmlspecialchars($_POST['kelengkapan']);
        
            $dataLemari = [
                'id_alternatif' => $id_alternatif,
                'nama_alternatif' => $nama_alternatif,
                'gambar' => $namaFile,
                'design' => $design,
                'merek' => $merek
            ];
            
            $dataKecAltKrit = [
                'C1' => $harga,
                'C2' => $kualitas,
                'C3' => $volume,
                'C4' => $kelengkapan
            ];
            $Lemari->editDataLemari($dataLemari,$dataKecAltKrit);
        } else {
            return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
        }
    } else {
        $harga = htmlspecialchars($_POST['harga']);
        $kualitas = htmlspecialchars($_POST['kualitas']);
        $volume = htmlspecialchars($_POST['volume']);
        $kelengkapan = htmlspecialchars($_POST['kelengkapan']);
    
        $dataLemari = [
            'id_alternatif' => $id_alternatif,
            'nama_alternatif' => $nama_alternatif,
            'gambar' => $_POST['gambar_lama'],
            'design' => $design,
            'merek' => $merek
        ];
        
        $dataKecAltKrit = [
            'C1' => $harga,
            'C2' => $kualitas,
            'C3' => $volume,
            'C4' => $kelengkapan
        ];
        $Lemari->editDataLemari($dataLemari,$dataKecAltKrit);
    }
}

if(isset($_POST['hapus'])){
    $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
    $Lemari->hapusDataLemari($id_alternatif);
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
                        <h6 class="m-0 font-weight-bold text-primary">Daftar Lemari</h6>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataAlternatif as $key => $alternatif):?>
                                    <tr>
                                        <td><?=$key+1?></td>
                                        <td><?=$alternatif['nama_alternatif'];?></td>
                                        <td><a href="../images/<?=$alternatif['gambar'];?>" data-lightbox="image-1"
                                                data-title="<?=$alternatif['nama_alternatif'];?>">
                                                <img style="width: 50px; height: 50px;"
                                                    src="../images/<?=$alternatif['gambar'];?>"
                                                    alt="Gambar <?=$alternatif['nama_alternatif'];?>">
                                            </a></td>
                                        <td><?=$alternatif['design'];?></td>
                                        <td><?=$alternatif['nama_C1'];?></td>
                                        <td><?=$alternatif['nama_C2'];?></td>
                                        <td><?=$alternatif['nama_C3'];?></td>
                                        <td><?=$alternatif['nama_C4'];?></td>
                                        <td><?=$alternatif['merek'] != NULL ? $alternatif['merek']:'-';?></td>
                                        <td>
                                            <button data-toggle="modal"
                                                data-target="#edit<?=$alternatif['id_alternatif'];?>" type="button"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button data-toggle="modal"
                                                data-target="#hapus<?=$alternatif['id_alternatif'];?>" type="button"
                                                class="btn btn-sm btn-danger">Hapus</button>
                                        </td>
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
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="card-body">
                        <small class="text-danger">(*) Wajib</small>
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label">Nama Alternatif <small
                                    class="text-danger">*</small></label>
                            <input type="text" class="form-control" required name="nama_alternatif"
                                id="exampleFormControlInput1" placeholder="Nama Alternatif" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="gambar" class="form-label">Gambar <small class="text-danger">*</small></label>
                            <input type="file" accept=".jpg, .jpeg, .png" class="form-control" name="gambar" id="gambar"
                                required placeholder="Gambar" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="merek" class="form-label">Merek <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" name="merek" id="merek" required
                                placeholder="Merek" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="design" class="form-label">Design <small class="text-danger">*</small></label>
                            <select class="form-control" name="design" required aria-label="Default select example">
                                <option value="">-- Pilih Design --</option>
                                <option value="Motif">Motif</option>
                                <option value="Warna">Warna</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="harga" class="form-label">Harga <small class="text-danger">*</small></label>
                            <select class="form-control" name="harga" required aria-label="Default select example">
                                <option value="">-- Pilih Harga --</option>
                                <?php foreach ($dataSubHarga as $key => $harga):?>
                                <option value="<?=$harga['id_sub_kriteria'];?>">
                                    <?=$harga['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="kualitas" class="form-label">Kualitas <small
                                    class="text-danger">*</small></label>
                            <select class="form-control" name="kualitas" required aria-label="Default select example">
                                <option value="">-- Pilih Kualitas --</option>
                                <?php foreach ($dataSubKualitas as $key => $kualitas):?>
                                <option value="<?=$kualitas['id_sub_kriteria'];?>">
                                    <?=$kualitas['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="volume" class="form-label">Volume <small class="text-danger">*</small></label>
                            <select class="form-control" name="volume" required aria-label="Default select example">
                                <option value="">-- Pilih Volume --</option>
                                <?php foreach ($dataSubVolume as $key => $volume):?>
                                <option value="<?=$volume['id_sub_kriteria'];?>">
                                    <?=$volume['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="kelengkapan" class="form-label">Kelengkapan <small
                                    class="text-danger">*</small></label>
                            <select class="form-control" name="kelengkapan" required
                                aria-label="Default select example">
                                <option value="">-- Pilih Kelengkapan --</option>
                                <?php foreach ($dataSubKelengkapan as $key => $kelengkapan):?>
                                <option value="<?=$kelengkapan['id_sub_kriteria'];?>">
                                    <?=$kelengkapan['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="edit<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <div class="card-body">
                        <small class="text-danger">(*) Wajib</small>
                        <div class="">
                            <label for="exampleFormControlInput1" class="form-label">Nama Alternatif <small
                                    class="text-danger">*</small></label>
                            <input type="text" class="form-control" required name="nama_alternatif"
                                value="<?=$alternatif['nama_alternatif'];?>" id="exampleFormControlInput1"
                                placeholder="Nama Alternatif" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <input type="hidden" name="gambar_lama" value="<?=$alternatif['gambar'];?>">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" accept=".jpg, .jpeg, .png" class="form-control"
                                value="<?=$alternatif['gambar'];?>" name="gambar" id="gambar" placeholder="Gambar" />
                            <small><i>Jika gambar tidak diubah, maka tidak perlu diupload lagi.</i></small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="design" class="form-label">Design <small class="text-danger">*</small></label>
                            <select class="form-control" name="design" required aria-label="Default select example">
                                <option value="">-- Pilih Design --</option>
                                <?php foreach ($dataDesign as $key => $design):?>
                                <option <?=$design == $alternatif['design'] ? 'selected':'';?> value="<?=$design;?>">
                                    <?=$design;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="merek" class="form-label">Merek <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" value="<?=$alternatif['merek'];?>" name="merek"
                                id="merek" required placeholder="Merek" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="harga" class="form-label">Harga <small class="text-danger">*</small></label>
                            <select class="form-control" name="harga" required aria-label="Default select example">
                                <option value="">-- Pilih Harga --</option>
                                <?php foreach ($dataSubHarga as $key => $harga):?>
                                <option <?=$harga['id_sub_kriteria'] == $alternatif['id_sub_C1'] ? 'selected':'';?>
                                    value="<?=$harga['id_sub_kriteria'];?>">
                                    <?=$harga['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="kualitas" class="form-label">Kualitas <small
                                    class="text-danger">*</small></label>
                            <select class="form-control" name="kualitas" required aria-label="Default select example">
                                <option value="">-- Pilih Kualitas --</option>
                                <?php foreach ($dataSubKualitas as $key => $kualitas):?>
                                <option <?=$kualitas['id_sub_kriteria'] == $alternatif['id_sub_C2'] ? 'selected':'';?>
                                    value="<?=$kualitas['id_sub_kriteria'];?>">
                                    <?=$kualitas['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="volume" class="form-label">Volume <small class="text-danger">*</small></label>
                            <select class="form-control" name="volume" required aria-label="Default select example">
                                <option value="">-- Pilih Volume --</option>
                                <?php foreach ($dataSubVolume as $key => $volume):?>
                                <option <?=$volume['id_sub_kriteria'] == $alternatif['id_sub_C3'] ? 'selected':'';?>
                                    value="<?=$volume['id_sub_kriteria'];?>">
                                    <?=$volume['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <label for="kelengkapan" class="form-label">Kelengkapan <small
                                    class="text-danger">*</small></label>
                            <select class="form-control" name="kelengkapan" required
                                aria-label="Default select example">
                                <option value="">-- Pilih Kelengkapan --</option>
                                <?php foreach ($dataSubKelengkapan as $key => $kelengkapan):?>
                                <option
                                    <?= $kelengkapan['id_sub_kriteria'] == $alternatif['id_sub_C4'] ? 'selected':'';?>
                                    value="<?=$kelengkapan['id_sub_kriteria'];?>">
                                    <?=$kelengkapan['nama_sub_kriteria'];?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="hapus<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus alternatif <strong>
                            <?=$alternatif['nama_alternatif'];?></strong> ?</p>
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