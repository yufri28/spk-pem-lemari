<?php 
//    session_start();
   require_once '../config.php';

   class Penilaian {
       private $db;
   
       /**
        * Class constructor.
        */
       public function __construct()
       {
           $this->db = connectDatabase();
       }
   
       public function getDataAlternatif()
       {
            return $this->db->query("SELECT id_alternatif, nama_alternatif FROM alternatif");
       }

       public function getDataFasilitas()
       {
            return $this->db->query(
                "SELECT k.id_kriteria, k.nama_kriteria, sk.id_sub_kriteria, sk.nama_sub_kriteria 
                FROM kriteria k JOIN sub_kriteria sk ON sk.f_id_kriteria = k.id_kriteria 
                WHERE k.nama_kriteria = 'Fasilitas'"
                );
       }

       public function getDataJarak()
       {
            return $this->db->query(
                "SELECT k.id_kriteria, k.nama_kriteria, sk.id_sub_kriteria, sk.nama_sub_kriteria 
                FROM kriteria k JOIN sub_kriteria sk ON sk.f_id_kriteria = k.id_kriteria 
                WHERE k.nama_kriteria = 'Jarak'"
                );
       }

       public function getDataBiaya()
       {
            return $this->db->query(
                "SELECT k.id_kriteria, k.nama_kriteria, sk.id_sub_kriteria, sk.nama_sub_kriteria 
                FROM kriteria k JOIN sub_kriteria sk ON sk.f_id_kriteria = k.id_kriteria 
                WHERE k.nama_kriteria = 'Biaya'"
                );
       }
       public function getDataLuasKamar()
       {
            return $this->db->query(
                "SELECT k.id_kriteria, k.nama_kriteria, sk.id_sub_kriteria, sk.nama_sub_kriteria 
                FROM kriteria k JOIN sub_kriteria sk ON sk.f_id_kriteria = k.id_kriteria 
                WHERE k.nama_kriteria = 'Luas Kamar'"
                );
       }
       public function getDataKeamanan()
       {
            return $this->db->query(
                "SELECT k.id_kriteria, k.nama_kriteria, sk.id_sub_kriteria, sk.nama_sub_kriteria 
                FROM kriteria k JOIN sub_kriteria sk ON sk.f_id_kriteria = k.id_kriteria 
                WHERE k.nama_kriteria = 'Keamanan'"
                );
       }

        public function getDataPenilaian()
        {
            return $this->db->query(
                "SELECT a.nama_alternatif, a.id_alternatif,
                MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN kak.id_alt_kriteria END) AS id_sub_C1,
                MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN kak.id_alt_kriteria END) AS id_sub_C2,
                MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN kak.id_alt_kriteria END) AS id_sub_C3,
                MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN kak.id_alt_kriteria END) AS id_sub_C4,
                MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN kak.id_alt_kriteria END) AS id_sub_C5,
                MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.nama_sub_kriteria END) AS nama_C1,
                MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.nama_sub_kriteria END) AS nama_C2,
                MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.nama_sub_kriteria END) AS nama_C3,
                MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.nama_sub_kriteria END) AS nama_C4,
                MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.nama_sub_kriteria END) AS nama_C5
                FROM alternatif a
                JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                GROUP BY a.nama_alternatif;"
            );
        }
        public function tambahPenilaian($idAlternatif, $dataKriteria, $dataSubKriteria)
       {
           $stmt = $this->db->prepare("SELECT * FROM kecocokan_alt_kriteria WHERE f_id_alternatif=? AND f_id_user=1");
           $stmt->bind_param("i", $idAlternatif);
           $stmt->execute();
           $result = $stmt->get_result();
           if ($result->num_rows <= 0) {
                // $count = 0; // Variabel untuk menghitung jumlah baris data yang sama
                for ($i = 0; $i < count($dataKriteria); $i++) {
                    $idKriteria = $dataKriteria[$i];
                    $idSubKriteria = $dataSubKriteria[$i];
                    // $count++; // Increment count jika ada baris data yang sama
                    $stmtInsert = $this->db->query("INSERT INTO kecocokan_alt_kriteria (id_alt_kriteria, f_id_alternatif, f_id_kriteria, f_id_sub_kriteria, f_id_user) VALUES (NULL, $idAlternatif, '$idKriteria', $idSubKriteria, 1)");
                    if($stmtInsert){
                        return $_SESSION['success'] = 'Data berhasil disimpan!';
                    }else{
                        return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
                    }
                }
            } 
            else {
                return $_SESSION['error'] = 'Tidak bisa memasukan data dengan nama Kost yang sama!.';
            }
            $stmt->close();
        }

        public function editPenilaian($dataAltKriteria, $idAlternatif, $dataKriteria, $dataSubKriteria)
       {
           $stmt = $this->db->prepare("SELECT * FROM kecocokan_alt_kriteria WHERE f_id_alternatif=? AND f_id_user=1");
           $stmt->bind_param("i", $idAlternatif);
           $stmt->execute();
           $result = $stmt->get_result();
           if ($result->num_rows <= 0) {
                // $count = 0; // Variabel untuk menghitung jumlah baris data yang sama
                for ($i = 0; $i < count($dataKriteria); $i++) {
                    $idKriteria = $dataKriteria[$i];
                    $idSubKriteria = $dataSubKriteria[$i];
                    // $count++; // Increment count jika ada baris data yang sama
                    $stmtInsert = $this->db->query("INSERT INTO kecocokan_alt_kriteria (id_alt_kriteria, f_id_alternatif, f_id_kriteria, f_id_sub_kriteria, f_id_user) VALUES (NULL, $idAlternatif, '$idKriteria', $idSubKriteria, 1)");
                    if($stmtInsert){
                        return $_SESSION['success'] = 'Data berhasil disimpan!';
                    }else{
                        return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
                    }
                }
            } 
            else {
                return $_SESSION['error'] = 'Tidak bisa memasukan data dengan nama Kost yang sama!.';
            }
            $stmt->close();
        }

        public function tambahPenilaianBobot($dataPenilaian,$id_user)
        {
            $C1 = 0;
            $C2 = 0;
            $C3 = 0;
            $C4 = 0;
            $C5 = 0;
            foreach ($dataPenilaian as $key => $value) {
               switch ($key) {
                case "Fasilitas":
                    $C1 = $value;
                    break;
                case "Jarak":
                    $C2 = $value;
                    break;
                case "Biaya":
                    $C3 = $value;
                    break;
                case "Luas Kamar":
                    $C4 = $value;
                    break;
                case "Keamanan":
                    $C5 = $value;
                    break;
               }
            }
            $stmt = $this->db->prepare("SELECT * FROM bobot_kriteria WHERE f_id_user=?");
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows <= 0) {
                $stmtInsert = $this->db->query("INSERT INTO bobot_kriteria (id_bobot, C1, C2, C3, C4, C5, f_id_user) VALUES (NULL, '$C1', '$C2', '$C3', '$C4', '$C5', '$id_user')");
                if($stmtInsert){
                    return $_SESSION['success'] = 'Data berhasil ditambahkan!';
                }else{
                    return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
                }
             } 
             else {
                 return $_SESSION['error'] = 'Data sudah ada!';
             }
             $stmt->close();
        }
        public function editPenilaianBobot($id,$dataPenilaian)
        {
            $C1 = 0;
            $C2 = 0;
            $C3 = 0;
            $C4 = 0;
            $C5 = 0;
            foreach ($dataPenilaian as $key => $value) {
               switch ($key) {
                case "Fasilitas":
                    $C1 = $value;
                    break;
                case "Jarak":
                    $C2 = $value;
                    break;
                case "Biaya":
                    $C3 = $value;
                    break;
                case "Luas Kamar":
                    $C4 = $value;
                    break;
                case "Keamanan":
                    $C5 = $value;
                    break;
               }
            }
            $update = $this->db->query("UPDATE bobot_kriteria SET C1=$C1,C2=$C2,C3=$C3,C4=$C4,C5=$C5 WHERE id_bobot='$id'");
            if($update){
                return $_SESSION['success'] = 'Data berhasil diedit!';
            }else{
                return $_SESSION['error'] = 'Terjadi kesalahan dalam menyimpan data.';
            }
        }
        public function tambahTampung($dataTampung,$id_user)
        {
            $C1 = $dataTampung[0];
            $C2 =  $dataTampung[1];
            $C3 =  $dataTampung[2];
            $C4 =  $dataTampung[3];
            $C5 =  $dataTampung[4];
            $this->db->query("INSERT INTO tabel_tampung (id, prio1, prio2, prio3, prio4, prio5, f_id_user) VALUES (NULL, '$C1', '$C2', '$C3', '$C4', '$C5', '$id_user')");
        }
        public function editTampung($id,$dataTampung)
        {
            $C1 = $dataTampung[0];
            $C2 =  $dataTampung[1];
            $C3 =  $dataTampung[2];
            $C4 =  $dataTampung[3];
            $C5 =  $dataTampung[4];

            $this->db->query("UPDATE tabel_tampung SET prio1='$C1',prio2='$C2',prio3='$C3',prio4='$C4',prio5='$C5' WHERE id='$id'");
        }


    }
   
   $penilaian = new Penilaian();
?>