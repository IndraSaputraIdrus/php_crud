<?php

// functions untuk melakukan koneksi ke database
function koneksi()
{
  // menampung atribut untuk koneksi ke dalam varibel
  $db_server  = 'localhost';
  $db_user = 'root';
  $db_pass = '';
  $db_name = 'Quince_TIK';

  // melakukan koneksi & mengembalikan data nya
  return mysqli_connect($db_server, $db_user, $db_pass, $db_name);
}

// function untuk menampilkan seluruh data dari tabel siswa
function queryAllSiswa()
{
  // menampung function koneksi ke dalam variabel
  $conn = koneksi();

  // melakukan query ke database
  $sql = "SELECT * FROM siswa";
  $result = mysqli_query($conn, $sql);


  // jika data yang dibutuhkan cuman 1 baris saja / 1 data 
  if (mysqli_num_rows($result) == 1) {
    return mysqli_fetch_assoc($result);
  }

  // mengambil data dari database
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

// fungsi mengambil data berdasarkan id
function queryById($id)
{
  $conn = koneksi();

  // menggunakan prepared statement agar terhindar dari sql injection
  $query = "SELECT * FROM siswa WHERE id = ?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
  }
}

// function tambah data
function tambah($data)
{
  $conn = koneksi();

  $nama = $data['nama'];
  $no_urut = $data['no_urut'];
  $email = $data['email'];
  $jurusan = $data['jurusan'];
  $jenis_kelamin = $data['jenis_kelamin'];
  $gambar = $data['gambar'];

  // query insert 
  $query = "INSERT INTO `siswa` (`nama`, `no_urut`, `email`, `jurusan`, `jenis_kelamin`, `gambar`) VALUES (?,?,?,?,?,?);";

  // prepared statement (dengan cara yg sedikit berbeda dari script yg diatas
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, 'sissss', $nama, $no_urut, $email, $jurusan, $jenis_kelamin, $gambar);
  mysqli_stmt_execute($stmt);

  return mysqli_affected_rows($conn);
}
