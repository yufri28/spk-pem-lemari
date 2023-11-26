<?php 

    require_once '../config.php';
    class Lemari{
        private $db;
        public function __construct()
        {
            $this->db = connectDatabase();
        }

        public function getLemari(){
            return $this->db->query("SELECT a.nama_alternatif, a.id_alternatif, a.gambar, a.merek, a.design, kak.id_alt_kriteria,
                MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN kak.id_alt_kriteria END) AS id_alt_C1,
                MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN kak.id_alt_kriteria END) AS id_alt_C2,
                MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN kak.id_alt_kriteria END) AS id_alt_C3,
                MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN kak.id_alt_kriteria END) AS id_alt_C4,
                MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN kak.f_id_sub_kriteria END) AS id_sub_C1,
                MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN kak.f_id_sub_kriteria END) AS id_sub_C2,
                MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN kak.f_id_sub_kriteria END) AS id_sub_C3,
                MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN kak.f_id_sub_kriteria END) AS id_sub_C4,
                MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.nama_sub_kriteria END) AS nama_C1,
                MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.nama_sub_kriteria END) AS nama_C2,
                MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.nama_sub_kriteria END) AS nama_C3,
                MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.nama_sub_kriteria END) AS nama_C4
                FROM alternatif a
                JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                GROUP BY a.nama_alternatif ORDER BY a.id_alternatif DESC;
            ");
        }
        public function getSubHarga()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C1'"
           );
        }
        public function getSubKualitas()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C2'"
           );
        }
        public function getSubVolume()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C3'"
           );
        }
        public function getSubKelengkapan()
        {
           return $this->db->query(
                "SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C4'"
           );
        } 

        // CRUD
        public function addDataLemari($dataAlternatif = [], $dataKecAltKrit = [])
        {
            if (empty($dataAlternatif) && empty($dataKecAltKrit)) {
                return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
            }

            $nama_alternatif = $dataAlternatif['nama_alternatif'];
            $gambar = $dataAlternatif['gambar'];
            $design = $dataAlternatif['design'];
            $merek = $dataAlternatif['merek'];

            $cekData = $this->db->query("SELECT * FROM `alternatif` WHERE LOWER(nama_alternatif) = '" . strtolower($dataAlternatif['nama_alternatif']) . "'");
            if ($cekData->num_rows > 0) {
                return $_SESSION['error'] = 'Data sudah ada!';
            }

            $insertAlternatif = $this->db->query(
                "INSERT INTO alternatif (id_alternatif, nama_alternatif, gambar,design,merek) VALUES (NULL, '$nama_alternatif', '$gambar','$design','$merek')"
            );

            if ($insertAlternatif) {
                $id_alternatif = $this->db->insert_id;
                foreach ($dataKecAltKrit as $key => $id_sub_kriteria) {
                    $insertKecAltKrit = $this->db->query("INSERT INTO kec_alt_kriteria (id_alt_kriteria, f_id_alternatif, f_id_kriteria, f_id_sub_kriteria) VALUES (NULL, '$id_alternatif', '$key', '$id_sub_kriteria')");
                }
                if ($insertKecAltKrit && $this->db->affected_rows > 0) {
                    return $_SESSION['success'] = 'Data berhasil disimpan!';
                } else {
                    return $_SESSION['error'] = 'Data gagal disimpan!';
                }
            } else {
                return $_SESSION['error'] = 'Data gagal disimpan!';
            }
        }

        public function editDataLemari($dataAlternatif = [], $dataKecAltKrit = [])
        {
            if (empty($dataAlternatif) && empty($dataKecAltKrit)) {
                return $_SESSION['error'] = 'Tidak ada data yang dikirim!';
            }
            $id_alternatif = $dataAlternatif['id_alternatif'];
            $nama_alternatif = $dataAlternatif['nama_alternatif'];
            $gambar = $dataAlternatif['gambar'];
            $design = $dataAlternatif['design'];
            $merek = $dataAlternatif['merek'];
            $updateAlternatif = $this->db->query(
                "UPDATE alternatif SET nama_alternatif = '$nama_alternatif',gambar='$gambar',design='$design',merek='$merek' WHERE id_alternatif = $id_alternatif"
            );

            if ($updateAlternatif) {
                // Update data kec_alt_kriteria
                foreach ($dataKecAltKrit as $key => $id_sub_kriteria) {
                    $updateKecAltKrit = $this->db->query("UPDATE kec_alt_kriteria SET f_id_sub_kriteria = '$id_sub_kriteria' WHERE f_id_alternatif = '$id_alternatif' AND f_id_kriteria = '$key'");
                }
                if ($updateKecAltKrit || $this->db->affected_rows > 0) {
                    return $_SESSION['success'] = 'Data berhasil diupdate!';
                } 
                else {
                    return $_SESSION['error'] = 'Data gagal diupdate!';
                }
            } else {
                return $_SESSION['error'] = 'Data gagal diupdate!';
            }
        }

        public function hapusDataLemari($id_alternatif)
        {
            $stmtDelete = $this->db->prepare("DELETE FROM alternatif WHERE id_alternatif=?");
            $stmtDelete->bind_param("i", $id_alternatif);
            $stmtDelete->execute();

            if ($stmtDelete->affected_rows > 0) {
                $_SESSION['success'] = 'Data berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
            }
            $stmtDelete->close();
        }

        public function getRiwayat()
        {
            return $this->db->query("SELECT * FROM riwayat ORDER BY id_riwayat DESC");
        }

        public function hapusRiwayat($id=0)
        {
            $stmtDelete = $this->db->prepare("DELETE FROM riwayat WHERE id_riwayat=?");
            $stmtDelete->bind_param("i", $id);
            $stmtDelete->execute();
            if ($stmtDelete->affected_rows > 0) {
                $_SESSION['success'] = 'Data berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan dalam menghapus data.';
            }
            $stmtDelete->close();
        }

        // End CRUD 
        public function getDataRiwayat($id_alternatif,$c1=0,$c2=0,$c3=0,$c4=0)
        {
            return $this->db->query("SELECT a.nama_alternatif, a.id_alternatif, a.gambar, a.design, a.merek,
            MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.nama_sub_kriteria END) AS nama_C1,
            MAX(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.nama_sub_kriteria END) AS nama_C2,
            MAX(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.nama_sub_kriteria END) AS nama_C3,
            MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.nama_sub_kriteria END) AS nama_C4,        
            
            (MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria)) AS utilitas_C1,
            
            (MAX(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria)) AS utilitas_C2,
            
            (MAX(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria)) AS utilitas_C3,
            
            (MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria)) AS utilitas_C4,            
            (($c1/($c1+$c2+$c3+$c4)) * (MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Harga' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))) + 
            (($c2/($c1+$c2+$c3+$c4)) * (MAX(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kualitas' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))) +
            (($c3/($c1+$c2+$c3+$c4)) * (MAX(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Volume' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))) +
            (($c4/($c1+$c2+$c3+$c4)) * (MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))/((SELECT MAX(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) - (SELECT MIN(CASE WHEN k.nama_kriteria = 'Kelengkapan' THEN sk.bobot_sub_kriteria END)
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria))) AS preferensi
            FROM alternatif a
            JOIN kec_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
            JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
            JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria WHERE a.id_alternatif = $id_alternatif
            GROUP BY a.nama_alternatif ORDER BY preferensi DESC LIMIT 1;");
        } 
    }

    $Lemari = new Lemari();

?>