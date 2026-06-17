<?php

    include '../config/koneksi.php';

    $id = $_GET['id'];

    mysqli_query(
        $koneksi,
        "DELETE FROM sparepart
        WHERE id_sparepart='$id'"
    );

    header("Location: sparepart.php");

?>