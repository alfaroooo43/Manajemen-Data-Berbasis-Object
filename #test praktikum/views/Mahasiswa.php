<?php
class Mahasiswa {

private $conn;
public function __construct($db) {
$this->conn = $db;
}
public function getAll() {
return $this->conn->query("SELECT * FROM mahasiswa");
}
public function getById($id) {
return $this->conn->query("SELECT * FROM mahasiswa WHERE id=$id");
}
public function create($nama, $jurusan) {
$nama = $this->conn->real_escape_string($nama);
$jurusan = $this->conn->real_escape_string($jurusan);
$result = $this->conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS next_id FROM mahasiswa");
$row = $result->fetch_assoc();
$id = $row['next_id'];
return $this->conn->query("INSERT INTO mahasiswa(id,nama,jurusan)
VALUES($id,'$nama','$jurusan')");
}
public function update($id, $nama, $jurusan) {
return $this->conn->query("UPDATE mahasiswa SET nama='$nama',
jurusan='$jurusan' WHERE id=$id");
}
public function delete($id) {
return $this->conn->query("DELETE FROM mahasiswa WHERE id=$id");
}
}
?>
