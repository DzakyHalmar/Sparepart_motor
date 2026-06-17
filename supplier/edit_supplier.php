<?php
    include "../config/koneksi.php";

    $id = $_GET['id'];

    $data = mysqli_query(
        $koneksi,
        "SELECT * FROM supplier
        WHERE id_supplier='$id'"
    );

    $supplier = mysqli_fetch_assoc($data);

    if(isset($_POST['update'])){

        $nama = $_POST['nama_supplier'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];

        mysqli_query(
            $koneksi,
            "UPDATE supplier SET
            nama_supplier='$nama',
            alamat='$alamat',
            telepon='$telepon'
            WHERE id_supplier='$id'"
        );

        echo "
        <script>
            alert('Supplier berhasil diupdate');
            window.location='supplier.php';
        </script>
        ";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard">

        <div class="navbar-admin">
            <h4>MotoParts Admin</h4>

            <a href="supplier.php" class="logout-btn">
                Kembali
            </a>
        </div>

        <div class="container py-5">

            <h1 class="dashboard-title text-center">
                Edit Supplier
            </h1>

            <div class="d-flex justify-content-center">

                <div class="login-card">

                    <form method="POST">

                        <div class="mb-3">
                            <label>Nama Supplier</label>
    
                            <input
                                type="text"
                                name="nama_supplier"
                                class="form-control"
                                value="<?= $supplier['nama_supplier']; ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Alamat</label>
    
                            <textarea
                            name="alamat"
                            class="form-control"
                            required><?= $supplier['alamat']; ?></textarea>
                        </div> 
                        
                        <div class="mb-3">
                            <label>Telepon</label>
    
                            <input
                            type="text"
                            name="telepon"
                            class="form-control"
                            value="<?= $supplier['telepon']; ?>"
                            required>
                        </div>                        
                        
                        <button
                            type="submit"
                            name="update"
                            class="btn btn-login">
                                Update Supplier
                        </button>                        

                    </form>


                </div>

            </div>

        </div>

    </div>
</body>
</html>