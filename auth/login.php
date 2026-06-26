<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MotoParts - Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body>
    <div class="hero">
        <div class="overlay">
            <div class="brand">
                <i class="fas fa-motorcycle logo-icon"></i>
                <h1>MotoParts</h1>
                <div class="line"></div>
                <h4>
                    Motorcycle Parts Management System
                </h4>
            </div>
            <div class="login-card">
                <h3>Administrator Login</h3>
                <form action="proses_login.php" method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label>Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login">
                        Login
                    </button>
                    <div class="copyright">
                        © 2026 MotoParts System
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>