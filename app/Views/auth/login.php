<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle ?? 'Masuk — AgriLink') ?></title>
  <meta name="description" content="Platform terintegrasi pengelolaan usaha pertanian AgriLink.">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- AgriLink CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

  <style>
    .auth-page-wrapper {
      min-height: 100vh;
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 50%, #ecfdf5 100%);
      padding: 2rem 1rem;
    }
    .auth-card {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-xl);
      max-width: 520px;
      width: 100%;
      padding: 2.5rem 2.25rem;
      box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      position: relative;
    }
    .auth-header {
      text-align: center;
      margin-bottom: 1.75rem;
    }
    .auth-logo {
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
      text-decoration: none;
    }
    .auth-header h1 {
      font-size: 1.5rem;
      font-weight: 800;
      color: var(--slate-900);
      margin-bottom: 0.35rem;
    }
    .auth-header p {
      font-size: 0.85rem;
      color: var(--slate-500);
    }
    .demo-persona-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.65rem;
      margin-top: 0.75rem;
    }
    .demo-persona-btn {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      padding: 0.65rem 0.75rem;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-md);
      background: #ffffff;
      text-decoration: none;
      color: var(--slate-700);
      font-size: 0.8rem;
      font-weight: 600;
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .demo-persona-btn:hover {
      border-color: var(--primary-500);
      background: var(--primary-50);
      transform: translateY(-1px);
    }
  </style>
</head>
<body>

<div class="auth-page-wrapper">
  <div class="auth-card">
    
    <!-- Header Brand -->
    <div class="auth-header">
      <a href="<?= base_url() ?>" class="auth-logo">
        <div class="brand-icon">
          <svg class="icon-svg" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/>
            <path d="M12 18V8"/>
            <path d="M12 12c-2.5-2.5-4-1-5 1"/>
            <path d="M12 9c2.5-2.5 4-1 5 1"/>
          </svg>
        </div>
        <span class="brand-title">Agri<span>Link</span></span>
      </a>
      <h1>Masuk ke Akun Anda</h1>
      <p>Kelola usaha tani terintegrasi dari perencanaan hingga penjualan hasil panen.</p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success">
        <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        <div><?= session()->getFlashdata('success') ?></div>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger">
        <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <div><?= session()->getFlashdata('error') ?></div>
      </div>
    <?php endif; ?>

    <!-- Form Login Utama -->
    <form action="<?= base_url('login') ?>" method="POST" autocomplete="on">
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" for="identifier">Email atau Username <span class="required">*</span></label>
        <div class="input-icon-wrapper">
          <input 
            type="text" 
            class="form-control" 
            id="identifier" 
            name="identifier" 
            value="<?= esc(old('identifier', 'petani@agrilink.id')) ?>" 
            placeholder="contoh: pakbudi atau petani@agrilink.id"
            required 
            autofocus
          >
        </div>
      </div>

      <div class="form-group">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
          <label class="form-label" for="password" style="margin-bottom:0;">Kata Sandi <span class="required">*</span></label>
        </div>
        <div class="input-icon-wrapper">
          <input 
            type="password" 
            class="form-control" 
            id="password" 
            name="password" 
            placeholder="Masukkan kata sandi Anda"
            value="password"
            required
          >
        </div>
        <div class="form-hint">Kata sandi default demo persona: <code>password</code></div>
      </div>

      <div style="margin-top:1.25rem;">
        <button type="submit" class="btn btn-primary btn-block" style="width:100%; padding:0.75rem; font-size:0.95rem;">
          Masuk ke Akun
        </button>
      </div>
    </form>

    <!-- Demo Persona Switcher (PRD Validation Quick Links) -->
    <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px dashed var(--slate-200);">
      <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--slate-400); margin-bottom:0.5rem; text-align:center;">
        Masuk Cepat Demo Persona (1-Klik):
      </div>

      <div class="demo-persona-grid">
        <a href="<?= base_url('demo-login/petani') ?>" class="demo-persona-btn" title="Login sebagai Petani">
          <svg class="icon-svg" viewBox="0 0 24 24" style="color:#16a34a;"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/></svg>
          <div style="flex:1; overflow:hidden;">
            <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Petani</div>
            <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Pak Budi Santoso</div>
          </div>
        </a>

        <a href="<?= base_url('demo-login/supplier') ?>" class="demo-persona-btn" title="Login sebagai Supplier Saprotan">
          <svg class="icon-svg" viewBox="0 0 24 24" style="color:#2563eb;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          <div style="flex:1; overflow:hidden;">
            <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Supplier</div>
            <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">CV Tani Makmur</div>
          </div>
        </a>

        <a href="<?= base_url('demo-login/buyer') ?>" class="demo-persona-btn" title="Login sebagai Pembeli Komoditas">
          <svg class="icon-svg" viewBox="0 0 24 24" style="color:#d97706;"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/></svg>
          <div style="flex:1; overflow:hidden;">
            <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Buyer</div>
            <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Resto Segar Nusantara</div>
          </div>
        </a>

        <a href="<?= base_url('demo-login/admin') ?>" class="demo-persona-btn" title="Login sebagai Administrator">
          <svg class="icon-svg" viewBox="0 0 24 24" style="color:#7c3aed;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
          <div style="flex:1; overflow:hidden;">
            <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Admin</div>
            <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Pengawas Sistem</div>
          </div>
        </a>
      </div>
    </div>

    <!-- Link ke Registrasi -->
    <div style="margin-top:1.5rem; text-align:center; font-size:0.85rem; color:var(--slate-600); border-top:1px solid var(--slate-100); padding-top:1.25rem;">
      Belum memiliki akun AgriLink? 
      <a href="<?= base_url('register') ?>" style="font-weight:700; color:var(--primary-700);">Daftar Sekarang &rarr;</a>
    </div>

  </div>
</div>

</body>
</html>

