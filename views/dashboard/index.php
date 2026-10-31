<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Dashboard — AgriLink') ?></title>
  <meta name="description" content="Dashboard Terintegrasi AgriLink">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- CSS AgriLink -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/style.css">

  <style>
    /* Styling Tambahan Dashboard MVC */
    .dashboard-profile-card {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-xl);
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: var(--shadow-sm);
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 1.25rem;
    }

    .profile-info-group {
      display: flex;
      align-items: center;
      gap: 1.25rem;
    }

    .profile-avatar-large {
      width: 64px;
      height: 64px;
      border-radius: var(--radius-full);
      background: linear-gradient(135deg, var(--primary-600), var(--primary-800));
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
      box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.3);
      flex-shrink: 0;
    }

    .profile-details h2 {
      font-size: 1.25rem;
      color: var(--slate-900);
      margin-bottom: 0.25rem;
    }

    .profile-meta-tags {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.8rem;
      color: var(--slate-500);
    }

    .badge-role {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      padding: 0.2rem 0.65rem;
      border-radius: var(--radius-full);
      font-size: 0.72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .badge-role-petani {
      background: #dcfce7;
      color: #15803d;
      border: 1px solid #bbf7d0;
    }

    .badge-role-supplier {
      background: #dbeafe;
      color: #1d4ed8;
      border: 1px solid #bfdbfe;
    }

    .badge-role-buyer {
      background: #fef3c7;
      color: #b45309;
      border: 1px solid #fde68a;
    }

    .badge-role-admin {
      background: #f3e8ff;
      color: #7e22ce;
      border: 1px solid #e9d5ff;
    }

    .quick-stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-lg);
      padding: 1.25rem;
      box-shadow: var(--shadow-xs);
      transition: transform var(--transition-fast), box-shadow var(--transition-fast);
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-md);
    }

    .stat-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 0.75rem;
    }

    .stat-icon {
      width: 36px;
      height: 36px;
      border-radius: var(--radius-md);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .stat-value {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--slate-900);
      line-height: 1.1;
      margin-bottom: 0.25rem;
    }

    .stat-label {
      font-size: 0.8rem;
      color: var(--slate-500);
      font-weight: 500;
    }

    .modules-section {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-xl);
      padding: 1.5rem;
      box-shadow: var(--shadow-sm);
    }

    .modules-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 1rem;
      margin-top: 1rem;
    }

    .module-card {
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-lg);
      padding: 1.25rem;
      text-decoration: none;
      color: inherit;
      background: var(--slate-50);
      transition: all var(--transition-fast);
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .module-card:hover {
      background: #ffffff;
      border-color: var(--primary-600);
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }

    .module-title {
      font-weight: 700;
      font-size: 0.95rem;
      color: var(--slate-900);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .module-desc {
      font-size: 0.8rem;
      color: var(--slate-500);
      line-height: 1.4;
    }

    .db-status-banner {
      background: #f0fdf4;
      border: 1px solid #bbf7d0;
      color: #166534;
      padding: 0.75rem 1rem;
      border-radius: var(--radius-md);
      font-size: 0.82rem;
      display: flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 1.25rem;
    }

    .btn-logout {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.55rem 1rem;
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
      border-radius: var(--radius-md);
      font-size: 0.82rem;
      font-weight: 600;
      text-decoration: none;
      transition: all var(--transition-fast);
    }

    .btn-logout:hover {
      background: #fecaca;
      color: #7f1d1d;
    }
  </style>
</head>
<body data-role="<?= htmlspecialchars($user['role']) ?>">

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="app-container">

    <!-- SIDEBAR NAVIGASI -->
    <aside class="app-sidebar" id="appSidebar">
      <div class="sidebar-header">
        <a href="<?= BASE_URL ?>/index.php?c=dashboard&a=index" class="sidebar-brand">
          <div class="brand-icon">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/><path d="M12 18V8"/><path d="M12 12c-2.5-2.5-4-1-5 1"/><path d="M12 9c2.5-2.5 4-1 5 1"/></svg>
          </div>
          <span class="brand-title">Agri<span>Link</span></span>
        </a>
      </div>

      <div class="sidebar-role-indicator">
        <span class="role-info-label">Peran Aktif</span>
        <span class="role-info-value">
          <span class="badge-role badge-role-<?= htmlspecialchars($user['role']) ?>">
            <?= strtoupper(htmlspecialchars($user['role'])) ?>
          </span>
        </span>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>
        
        <a href="<?= BASE_URL ?>/index.php?c=dashboard&a=index" class="nav-item active">
          <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          <span>Dashboard</span>
        </a>

        <?php if ($user['role'] === 'petani'): ?>
          <a href="<?= BASE_URL ?>/views/perencanaan-lahan.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            <span>Perencanaan Lahan</span>
          </a>

          <a href="<?= BASE_URL ?>/views/estimasi-modal.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
            <span>Estimasi Modal</span>
          </a>

          <a href="<?= BASE_URL ?>/views/marketplace-saprotan.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span>Marketplace Saprotan</span>
          </a>

          <a href="<?= BASE_URL ?>/views/catatan-panen.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>Pencatatan Panen</span>
          </a>

          <a href="<?= BASE_URL ?>/views/pasar-hasil-tani.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Pasar Hasil Tani</span>
          </a>

          <a href="<?= BASE_URL ?>/views/pesanan-transaksi.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Pesanan & Transaksi</span>
          </a>

        <?php elseif ($user['role'] === 'supplier'): ?>
          <a href="<?= BASE_URL ?>/views/supplier-katalog.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span>Katalog Produk Saprotan</span>
          </a>
          <a href="<?= BASE_URL ?>/views/supplier-pesanan.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/></svg>
            <span>Pesanan Masuk</span>
          </a>

        <?php elseif ($user['role'] === 'buyer'): ?>
          <a href="<?= BASE_URL ?>/views/buyer-katalog.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            <span>Katalog Komoditas Petani</span>
          </a>
          <a href="<?= BASE_URL ?>/views/buyer-pesanan.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/></svg>
            <span>Pesanan Saya</span>
          </a>

        <?php elseif ($user['role'] === 'admin'): ?>
          <a href="<?= BASE_URL ?>/views/admin-users.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <span>Manajemen Pengguna</span>
          </a>
          <a href="<?= BASE_URL ?>/views/admin-moderasi.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Moderasi Sistem</span>
          </a>
          <a href="<?= BASE_URL ?>/views/admin-audit.html" class="nav-item">
            <svg class="icon-svg" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
            <span>Audit Log</span>
          </a>
        <?php endif; ?>

        <div class="nav-section-title" style="margin-top:1rem;">Akun & Sesi</div>
        <a href="<?= BASE_URL ?>/index.php?c=auth&a=logout" class="nav-item" style="color:#dc2626;">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Keluar (Logout)</span>
        </a>
      </nav>

      <!-- Sidebar User Profile -->
      <div class="sidebar-footer">
        <div class="sidebar-user-card">
          <div class="user-avatar-circle">
            <?= strtoupper(substr($user['nama_lengkap'], 0, 2)) ?>
          </div>
          <div class="user-text-meta">
            <div class="user-display-name"><?= htmlspecialchars($user['nama_lengkap']) ?></div>
            <div class="user-subrole"><?= ucfirst(htmlspecialchars($user['role'])) ?> &bull; <?= htmlspecialchars($user['username']) ?></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="app-main">
      <header class="app-header">
        <div class="header-left">
          <div class="breadcrumb-area">
            <h1 class="page-main-title">Dashboard <?= ucfirst(htmlspecialchars($user['role'])) ?></h1>
            <div class="breadcrumb-trail">
              <span>AgriLink</span>
              <span class="breadcrumb-separator">/</span>
              <span>Autentikasi MVC Berhasil</span>
            </div>
          </div>
        </div>

        <div class="header-right">
          <a href="<?= BASE_URL ?>/index.php?c=auth&a=logout" class="btn-logout" title="Keluar dari akun">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Keluar</span>
          </a>
        </div>
      </header>

      <main class="page-body">
        
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash'])): ?>
          <?php foreach ($_SESSION['flash'] as $flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?>" style="margin-bottom:1.5rem; background:var(--success-bg); border:1px solid var(--success-border); color:var(--success-text); padding:1rem; border-radius:8px; display:flex; align-items:center; gap:0.75rem;">
              <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
              </svg>
              <div><?= $flash['message'] ?></div>
            </div>
          <?php endforeach; ?>
          <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Banner Status Database -->
        <div class="db-status-banner">
          <svg class="icon-svg" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/>
            <polyline points="12 6 12 12 16 14"/>
          </svg>
          <div>
            <strong>Autentikasi Database MySQL Aktif:</strong> Sesi pengguna diambil langsung dari tabel <code>users</code> pada database <code><?= DB_NAME ?></code> menggunakan enkripsi BCRYPT.
          </div>
        </div>

        <!-- Profil Card -->
        <div class="dashboard-profile-card">
          <div class="profile-info-group">
            <div class="profile-avatar-large">
              <?= strtoupper(substr($user['nama_lengkap'], 0, 2)) ?>
            </div>
            <div class="profile-details">
              <h2><?= htmlspecialchars($user['nama_lengkap']) ?></h2>
              <div class="profile-meta-tags">
                <span class="badge-role badge-role-<?= htmlspecialchars($user['role']) ?>">
                  <?= strtoupper(htmlspecialchars($user['role'])) ?>
                </span>
                <span>&bull;</span>
                <span>Username: <strong>@<?= htmlspecialchars($user['username']) ?></strong></span>
                <span>&bull;</span>
                <span>Email: <strong><?= htmlspecialchars($user['email']) ?></strong></span>
                <?php if (!empty($user['telepon'])): ?>
                  <span>&bull;</span>
                  <span>WhatsApp: <strong><?= htmlspecialchars($user['telepon']) ?></strong></span>
                <?php endif; ?>
              </div>
              <?php if (!empty($user['alamat'])): ?>
                <div style="font-size:0.8rem; color:var(--slate-600); margin-top:0.4rem;">
                  <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24" style="vertical-align:text-bottom; display:inline-block;">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                  </svg>
                  <?= htmlspecialchars($user['alamat']) ?>
                </div>
              <?php endif; ?>
            </div>
          </div>

          <div>
            <a href="<?= BASE_URL ?>/index.php?c=auth&a=logout" class="btn btn-outline" style="font-size:0.84rem;">
              <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
              Ganti Akun / Keluar
            </a>
          </div>
        </div>

        <!-- Stat Ringkasan -->
        <div class="quick-stats-grid">
          <div class="stat-card">
            <div class="stat-card-header">
              <span class="stat-label">ID Akun</span>
              <div class="stat-icon" style="background:var(--primary-100); color:var(--primary-800);">#</div>
            </div>
            <div class="stat-value"><?= htmlspecialchars((string) $user['id']) ?></div>
            <div style="font-size:0.75rem; color:var(--slate-500);">Terdaftar di Database</div>
          </div>

          <div class="stat-card">
            <div class="stat-card-header">
              <span class="stat-label">Peran Sistem</span>
              <div class="stat-icon" style="background:#e0f2fe; color:#0369a1;">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              </div>
            </div>
            <div class="stat-value" style="font-size:1.3rem;"><?= ucfirst(htmlspecialchars($user['role'])) ?></div>
            <div style="font-size:0.75rem; color:var(--slate-500);">Hak Akses Aktif</div>
          </div>

          <div class="stat-card">
            <div class="stat-card-header">
              <span class="stat-label">Status Keanggotaan</span>
              <div class="stat-icon" style="background:#fef3c7; color:#b45309;">
                <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              </div>
            </div>
            <div class="stat-value" style="font-size:1.3rem; color:var(--primary-700);">Premium Active</div>
            <div style="font-size:0.75rem; color:var(--slate-500);">Paket Usaha Tani Lengkap</div>
          </div>

          <div class="stat-card">
            <div class="stat-card-header">
              <span class="stat-label">Tanggal Bergabung</span>
              <div class="stat-icon" style="background:#f3e8ff; color:#7e22ce;">
                <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              </div>
            </div>
            <div class="stat-value" style="font-size:1.1rem;">
              <?= !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : date('d M Y') ?>
            </div>
            <div style="font-size:0.75rem; color:var(--slate-500);">Terverifikasi Sistem</div>
          </div>
        </div>

        <!-- Modul Navigasi PRD -->
        <div class="modules-section">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
              <h3 style="font-size:1.1rem; color:var(--slate-900);">Eksplorasi Fitur AgriLink</h3>
              <p style="font-size:0.82rem; color:var(--slate-500);">Akses modul terintegrasi sesuai alur usaha tani dari perencanaan hingga pasar.</p>
            </div>
          </div>

          <div class="modules-grid">
            <a href="<?= BASE_URL ?>/views/perencanaan-lahan.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:var(--primary-700);"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/></svg>
                Perencanaan Lahan
              </div>
              <div class="module-desc">Pencatatan komoditas, luas petak, jadwal tanam dan estimasi tanggal panen.</div>
            </a>

            <a href="<?= BASE_URL ?>/views/estimasi-modal.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:#2563eb;"><rect x="4" y="2" width="16" height="20" rx="2"/></svg>
                Estimasi Kebutuhan & Modal
              </div>
              <div class="module-desc">Kalkulasi bibit, pupuk, pestisida, serta biaya tenaga kerja sebelum masa tanam.</div>
            </a>

            <a href="<?= BASE_URL ?>/views/marketplace-saprotan.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:#d97706;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                Marketplace Saprotan
              </div>
              <div class="module-desc">Pesan sarana produksi pertanian langsung dari supplier resmi terpercaya.</div>
            </a>

            <a href="<?= BASE_URL ?>/views/catatan-panen.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:var(--primary-700);"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                Pencatatan Hasil Panen
              </div>
              <div class="module-desc">Dokumentasikan tonase panen aktual, mutu/grade, dan efisiensi produksi.</div>
            </a>

            <a href="<?= BASE_URL ?>/views/pasar-hasil-tani.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:#059669;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Pasar Hasil Tani
              </div>
              <div class="module-desc">Jual komoditas hasil panen langsung ke buyer, resto, dan pedagang besar.</div>
            </a>

            <a href="<?= BASE_URL ?>/views/pesanan-transaksi.html" class="module-card">
              <div class="module-title">
                <svg class="icon-svg" viewBox="0 0 24 24" style="color:#7c3aed;"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/></svg>
                Pesanan & Transaksi
              </div>
              <div class="module-desc">Pantau status pembayaran, nota pesanan, dan pengiriman barang end-to-end.</div>
            </a>
          </div>
        </div>

      </main>
    </div>

  </div>

</body>
</html>
