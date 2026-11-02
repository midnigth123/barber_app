<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barber Shop POS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #111111 0%, #1a1a1a 50%, #2c2c2c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            padding: 40px 30px;
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);
            color: #111;
            font-size: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            margin: 0 auto 20px auto;
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: #d4af37;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.15);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            color: #b0b0b0;
            margin-bottom: 8px;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #aa771c 100%);
            border: none;
            color: #111;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #e5be425b 0%, #bc8322 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.35);
            color: #000;
        }

        .alert {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b6b;
            font-size: 13px;
            border-radius: 12px;
        }
    </style>
</head>

<body>

    <div class="login-wrapper">
        <div class="login-card">

            <!-- Logo / Icon Barber -->
            <div class="brand-icon">
                <i class="bi bi-scissors"></i>
            </div>

            <div class="text-center mb-4">
                <h4 class="fw-bold text-white mb-1">Barber POS Panel</h4>
                <p class="text-muted small">Silakan masuk untuk mengelola sistem</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-1"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login/process') ?>" method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" name="username" class="form-control" required placeholder="Masukkan username admin/kasir">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-gold w-100">
                    Login <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">&copy; 2026 Barber Shop System</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>