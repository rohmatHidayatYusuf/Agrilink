<?php
session_start();

// Daftar hak akses pengguna
$dashboard = [
    'petani'   => 'index.php',
    'supplier' => 'supplier-dashboard.php',
    'buyer'    => 'buyer-dashboard.php',
    'admin'    => 'admin-dashboard.php'
];

// Proses pemilihan role
$error = '';

if (isset($_GET['role'])) {
    $role = strtolower(trim($_GET['role']));

    if (array_key_exists($role, $dashboard)) {
        $_SESSION['role'] = $role;

        // Arahkan ke dashboard sesuai role
        header("Location: " . $dashboard[$role]);
        exit;
    } else {
        $error = "Peran yang dipilih tidak valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Masuk — AgriLink</title>

    <meta name="description" content="Masuk ke platform terintegrasi pengelolaan usaha pertanian AgriLink.">

    <link rel="stylesheet" href="style.css">

    <style>
        .auth-page-wrapper {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--slate-50);
            padding: 1.5rem;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-xl);
            max-width: 460px;
            width: 100%;
            padding: 2.25rem 2rem;
            box-shadow: var(--shadow-lg);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .role-selection-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .role-select-card {
            border: 1px solid var(--slate-200);
            border-radius: var(--radius-md);
            padding: 0.75rem;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            color: var(--slate-700);
            transition: all var(--transition-fast);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
        }

        .role-select-card:hover {
            border-color: var(--primary-600);
            background: var(--primary-50);
            color: var(--primary-800);
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>

<body>

<div class="auth-page-wrapper">

    <div class="auth-card">

        <!-- Header -->
        <div class="auth-header">

            <div class="auth-logo">

                <div class="brand-icon">
                    <svg class="icon-svg" viewBox="0 0 24 24">
                        <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/>
                        <path d="M12 18V8"/>
                        <path d="M12 12c-2.5-2.5-4-1-5 1"/>
                        <path d="M12 9c2.5-2.5 4-1 5 1"/>
                    </svg>
                </div>

                <span class="brand-title">
                    Agri<span>Link</span>
                </span>

            </div>

            <h2 style="font-size: 1.25rem; color: var(--slate-900); margin-bottom: 0.25rem;">
                Masuk ke Sistem AgriLink
            </h2>

            <p style="font-size: 0.82rem; color: var(--slate-500);">
                Pilih peran Anda untuk langsung masuk ke dashboard sistem.
            </p>

        </div>

        <!-- Pesan Error -->
        <?php if (!empty($error)) : ?>
            <div class="error-message">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div style="font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); margin-bottom: 0.65rem;">
            Masuk Cepat Sesuai Hak Akses (Demo Persona):
        </div>

        <!-- Pilihan Role -->
        <div class="role-selection-box">

            <!-- Petani -->
            <a href="login.php?role=petani" class="role-select-card">

                <svg class="icon-svg" viewBox="0 0 24 24">
                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                </svg>

                <div style="font-weight: 700; font-size: 0.85rem;">
                    Petani
                </div>

                <div style="font-size: 0.7rem; color: var(--slate-500);">
                    Pak Budi Santoso
                </div>

            </a>

            <!-- Supplier -->
            <a href="login.php?role=supplier" class="role-select-card">

                <svg class="icon-svg" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/>
                    <circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>

                <div style="font-weight: 700; font-size: 0.85rem;">
                    Supplier
                </div>

                <div style="font-size: 0.7rem; color: var(--slate-500);">
                    CV Tani Makmur
                </div>

            </a>

            <!-- Buyer -->
            <a href="login.php?role=buyer" class="role-select-card">

                <svg class="icon-svg" viewBox="0 0 24 24">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                </svg>

                <div style="font-weight: 700; font-size: 0.85rem;">
                    Buyer
                </div>

                <div style="font-size: 0.7rem; color: var(--slate-500);">
                    Resto Segar Nusantara
                </div>

            </a>

            <!-- Admin -->
            <a href="login.php?role=admin" class="role-select-card">

                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>

                <div style="font-weight: 700; font-size: 0.85rem;">
                    Admin
                </div>

                <div style="font-size: 0.7rem; color: var(--slate-500);">
                    Pengawas Sistem
                </div>

            </a>

        </div>

        <!-- Tombol Dashboard Utama -->
        <div style="text-align: center; border-top: 1px solid var(--slate-200); padding-top: 1.25rem;">

            <a href="login.php?role=petani" class="btn btn-primary" style="width: 100%; text-decoration: none;">
                Masuk ke Dashboard Utama Petani &rarr;
            </a>

        </div>

    </div>

</div>

</body>
</html>