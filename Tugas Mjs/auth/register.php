<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - Bali Heritage Wear</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-soft">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow border-0">
        <div class="card-body p-4">
          <h4 class="fw-bold text-brown text-center mb-3">Daftar Akun</h4>

          <form action="process-register.php" method="post">
            <div class="mb-3">
              <label>Nama Lengkap</label>
              <input name="name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Email</label>
              <input name="email" type="email" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>No WhatsApp</label>
              <input name="whatsapp" class="form-control">
            </div>

            <div class="mb-3">
              <label>Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Daftar</button>

            <p class="text-center mt-3 small">
              Sudah punya akun?
              <a href="login.php" class="text-brown fw-semibold">Login</a>
            </p>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
