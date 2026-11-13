<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle ?? 'AgriLink — Sistem Manajemen Pertanian Terintegrasi') ?></title>
  <meta name="description" content="Platform digital terintegrasi perencanaan lahan, estimasi modal, saprotan, dan pasar hasil tani.">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- AgriLink CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body data-role="<?= esc(session()->get('user_role') ?? 'farmer') ?>">

  <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

  <div class="app-container">

    <!-- APP SIDEBAR -->
    <aside class="app-sidebar" id="appSidebar">
      <div class="sidebar-header">
        <a href="<?= base_url('dashboard') ?>" class="sidebar-brand">
          <div class="brand-icon">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/><path d="M12 18V8"/><path d="M12 12c-2.5-2.5-4-1-5 1"/><path d="M12 9c2.5-2.5 4-1 5 1"/></svg>
          </div>
          <span class="brand-title">Agri<span>Link</span></span>
        </a>
      </div>

      <?php $currentRole = strtolower((string) session()->get('user_role')); ?>
      <div class="sidebar-role-indicator">
        <span class="role-info-label">Peran Aktif</span>
        <span class="role-info-value"><?= strtoupper($currentRole) ?></span>
      </div>

      <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>

        <!-- Dashboard Universal -->
        <a href="<?= base_url('dashboard') ?>" class="nav-item <?= (url_is('dashboard') || url_is('/')) ? 'active' : '' ?>">
          <svg class="icon-svg" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          <span>Dashboard</span>
        </a>

        <?php if ($currentRole === 'petani'): ?>
          <div class="nav-section-title">Usaha Tani</div>

          <a href="<?= base_url('lahan') ?>" class="nav-item <?= url_is('lahan*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>
            <span>Perencanaan Lahan</span>
          </a>

          <a href="<?= base_url('estimasi') ?>" class="nav-item <?= url_is('estimasi*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
            <span>Estimasi Modal</span>
          </a>

          <a href="<?= base_url('saprotan') ?>" class="nav-item <?= url_is('saprotan*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span>Marketplace Saprotan</span>
          </a>

          <a href="<?= base_url('panen') ?>" class="nav-item <?= url_is('panen*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            <span>Pencatatan Panen</span>
          </a>

          <a href="<?= base_url('pasar') ?>" class="nav-item <?= url_is('pasar*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Pasar Hasil Tani</span>
          </a>

          <a href="<?= base_url('pesanan') ?>" class="nav-item <?= url_is('pesanan*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Pesanan & Transaksi</span>
          </a>

        <?php elseif ($currentRole === 'supplier'): ?>
          <div class="nav-section-title">Katalog & Penjualan</div>

          <a href="<?= base_url('supplier/katalog') ?>" class="nav-item <?= url_is('supplier/katalog*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span>Katalog Produk Saya</span>
          </a>

          <a href="<?= base_url('supplier/pesanan') ?>" class="nav-item <?= url_is('supplier/pesanan*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Pesanan Saprotan Masuk</span>
          </a>

        <?php elseif ($currentRole === 'buyer'): ?>
          <div class="nav-section-title">Pengadaan Komoditas</div>

          <a href="<?= base_url('buyer/katalog') ?>" class="nav-item <?= url_is('buyer/katalog*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Katalog Hasil Tani Segar</span>
          </a>

          <a href="<?= base_url('buyer/pesanan') ?>" class="nav-item <?= url_is('buyer/pesanan*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Pesanan Komoditas Saya</span>
          </a>

        <?php elseif ($currentRole === 'admin'): ?>
          <div class="nav-section-title">Administrasi Sistem</div>

          <a href="<?= base_url('admin/users') ?>" class="nav-item <?= url_is('admin/users*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Manajemen Pengguna</span>
          </a>

          <a href="<?= base_url('admin/moderasi') ?>" class="nav-item <?= url_is('admin/moderasi*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span>Moderasi Listing</span>
          </a>

          <a href="<?= base_url('admin/audit') ?>" class="nav-item <?= url_is('admin/audit*') ? 'active' : '' ?>">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            <span>Jejak Audit Sistem</span>
          </a>
        <?php endif; ?>

        <div class="nav-section-title">Akun & Layanan</div>
        <a href="<?= base_url('langganan') ?>" class="nav-item <?= url_is('langganan*') ? 'active' : '' ?>">
          <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          <span>Status Langganan</span>
        </a>

        <a href="<?= base_url('logout') ?>" class="nav-item" style="color: var(--danger-600);" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          <span>Keluar</span>
        </a>
      </nav>

      <div class="sidebar-footer">
        <div class="sidebar-user-card">
          <div class="user-avatar-circle"><?= strtoupper(substr((string) session()->get('user_name'), 0, 2)) ?></div>
          <div class="user-text-meta">
            <div class="user-display-name"><?= esc(session()->get('user_name')) ?></div>
            <div class="user-subrole"><?= esc(ucfirst($currentRole)) ?></div>
          </div>
        </div>
      </div>
    </aside>

    <!-- APP MAIN CONTENT -->
    <div class="app-main">
      <header class="app-header">
        <div class="header-left">
          <button class="mobile-menu-toggle" id="mobileMenuToggle" title="Buka Menu">
            <svg class="icon-svg" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
          </button>
          
          <div class="breadcrumb-area">
            <h1 class="page-main-title"><?= esc($pageTitle ?? 'AgriLink') ?></h1>
            <div class="breadcrumb-trail">
              <a href="<?= base_url('dashboard') ?>">AgriLink</a>
              <span>/</span>
              <span><?= esc(ucfirst($currentRole)) ?></span>
              <span>/</span>
              <span><?= esc($pageTitle ?? 'Beranda') ?></span>
            </div>
          </div>
        </div>

        <div class="header-right">
          <!-- Demo Quick Switcher Persona (Kompatibilitas Validasi PRD) -->
          <div class="header-role-switcher" title="Beralih Peran Pengguna (Demo)">
            <a href="<?= base_url('demo-login/petani') ?>" class="role-switch-btn <?= $currentRole === 'petani' ? 'active' : '' ?>">Petani</a>
            <a href="<?= base_url('demo-login/supplier') ?>" class="role-switch-btn <?= $currentRole === 'supplier' ? 'active' : '' ?>">Supplier</a>
            <a href="<?= base_url('demo-login/buyer') ?>" class="role-switch-btn <?= $currentRole === 'buyer' ? 'active' : '' ?>">Buyer</a>
            <a href="<?= base_url('demo-login/admin') ?>" class="role-switch-btn <?= $currentRole === 'admin' ? 'active' : '' ?>">Admin</a>
          </div>

          <a href="<?= base_url('logout') ?>" class="header-action-btn" title="Keluar Akun" onclick="return confirm('Keluar dari sistem?')">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </a>
        </div>
      </header>

      <main class="page-container">
        
        <!-- Flash Messages (Notifikasi Sesi) -->
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success">
            <svg class="icon-svg" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <div><?= session()->getFlashdata('success') ?></div>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div><?= session()->getFlashdata('error') ?></div>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('info')): ?>
          <div class="alert alert-info">
            <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
            <div><?= session()->getFlashdata('info') ?></div>
          </div>
        <?php endif; ?>

        <!-- Konten Halaman Spesifik -->
        <?= $this->renderSection('content') ?>

      </main>
    </div>
  </div>

  <div class="toast-shelf" id="toastShelf"></div>
  <script src="<?= base_url('assets/js/app.js') ?>"></script>
  <?= $this->renderSection('scripts') ?>
</body>
</html>

