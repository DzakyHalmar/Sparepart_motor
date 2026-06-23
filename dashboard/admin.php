    <!-- php -->
    <?php

    session_start();

    if(!isset($_SESSION['id_user'])){
        header("Location: ../auth/login.php");
        exit;
    }

    if($_SESSION['role'] != 'admin'){
        header("Location: kasir.php");
        exit;
    }

    ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <div class="dashboard">

        <div class="navbar-admin">
            <h4>MotoParts Admin</h4>

            <a href="../auth/logout.php" class="logout-btn">
                Logout
            </a>
        </div>

        <div class="dashboard-content container">

            <h1 class="dashboard-title text-center">
                Dashboard Admin
            </h1>

            <div class="row g-4">

                <!-- supplier -->
                <div class="col-md-3">
                    <a href="../supplier/supplier.php" class="menu-card">
                        <i class="fa-solid fa-users"></i>
                        <h3>Supplier</h3>
                    </a>
                </div>

                <!-- sparepart -->
                <div class="col-md-3">
                    <a href="../sparepart/sparepart.php" class="menu-card">
                        <i class="fa-solid fa-gears"></i>
                        <h3>Sparepart</h3>
                    </a>
                </div>

                <!-- user -->
                <div class="col-md-3">
                    <a href="../kategori/kategori.php" class="menu-card">
                        <i class="fa-solid fa-layer-group"></i>
                        <h3>Kategori</h3>
                    </a>
                </div>

                <!-- transaksi -->
                <div class="col-md-3">
                    <<a href="../laporan/cetak_laporan.php" class="menu-card">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <h3>Transaksi</h3>
                    </a>
                </div>

            </div>

        </div>

    </div>



</body>
</html>
