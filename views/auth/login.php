<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

<div class="auth-page-wrapper">
  <div class="auth-card">
    
    <!-- Header Brand -->
    <div class="auth-header">
      <a href="<?= BASE_URL ?>" class="auth-logo">
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

    <!-- Flash Messages (Notifikasi Sesi) -->
    <?php if (isset($_SESSION['flash'])): ?>
      <?php foreach ($_SESSION['flash'] as $key => $flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type'] ?? 'info') ?>">
          <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
            <?php if (($flash['type'] ?? '') === 'success'): ?>
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
              <polyline points="22 4 12 14.01 9 11.01"></polyline>
            <?php else: ?>
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
            <?php endif; ?>
          </svg>
          <div><?= $flash['message'] ?></div>
        </div>
      <?php endforeach; ?>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Error Form Umum -->
    <?php if (!empty($errors['general'])): ?>
      <div class="alert alert-danger">
        <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <div><?= htmlspecialchars($errors['general']) ?></div>
      </div>
    <?php endif; ?>

    <!-- Formulir Login Utama -->
    <form action="<?= BASE_URL ?>/index.php?c=auth&a=login" method="POST" autocomplete="on">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Input Email / Username -->
      <div class="form-group">
        <label for="identifier" class="form-label">Email atau Username</label>
        <div class="input-wrapper has-icon">
          <span class="input-icon">
            <svg class="icon-svg" viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
          </span>
          <input 
            type="text" 
            id="identifier" 
            name="identifier" 
            class="form-control <?= !empty($errors['identifier']) ? 'is-invalid' : '' ?>" 
            placeholder="nama@email.com atau username" 
            value="<?= htmlspecialchars($old['identifier'] ?? '') ?>"
            required
            autofocus
          >
        </div>
        <?php if (!empty($errors['identifier'])): ?>
          <span class="invalid-feedback"><?= htmlspecialchars($errors['identifier']) ?></span>
        <?php endif; ?>
      </div>

      <!-- Input Password -->
      <div class="form-group">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
          <label for="password" class="form-label" style="margin-bottom:0;">Kata Sandi</label>
        </div>
        <div class="input-wrapper has-icon has-toggle">
          <span class="input-icon">
            <svg class="icon-svg" viewBox="0 0 24 24">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
          </span>
          <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" 
            placeholder="Masukkan kata sandi akun"
            required
          >
          <button type="button" class="password-toggle-btn" data-target="password" title="Lihat/Sembunyikan Sandi">
            <!-- Icon Eye Open -->
            <svg class="icon-svg icon-eye" viewBox="0 0 24 24">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <!-- Icon Eye Off -->
            <svg class="icon-svg icon-eye-off" viewBox="0 0 24 24" style="display:none;">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
              <line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
          </button>
        </div>
        <?php if (!empty($errors['password'])): ?>
          <span class="invalid-feedback"><?= htmlspecialchars($errors['password']) ?></span>
        <?php endif; ?>
      </div>

      <!-- Remember Me -->
      <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; font-size:0.82rem;">
        <label style="display:flex; align-items:center; gap:0.45rem; cursor:pointer; color:var(--slate-600);">
          <input type="checkbox" name="remember" value="1" style="accent-color:var(--primary-700); cursor:pointer;">
          <span>Ingat saya di perangkat ini</span>
        </label>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary btn-block" style="padding:0.8rem 1.25rem;">
        <svg class="icon-svg" viewBox="0 0 24 24">
          <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
          <polyline points="10 17 15 12 10 7"></polyline>
          <line x1="15" y1="12" x2="3" y2="12"></line>
        </svg>
        <span>Masuk ke Akun</span>
      </button>
    </form>

    <!-- Quick Demo Login -->
    <div class="divider-text">Atau Masuk Cepat (Demo Persona)</div>

    <div class="demo-persona-grid">
      <!-- Petani -->
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=demoLogin&role=petani" class="demo-persona-btn" title="Login sebagai Pak Budi">
        <svg class="icon-svg" viewBox="0 0 24 24" style="color:var(--primary-700);">
          <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
        </svg>
        <div style="flex:1; overflow:hidden;">
          <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Petani</div>
          <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Pak Budi Santoso</div>
        </div>
      </a>

      <!-- Supplier -->
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=demoLogin&role=supplier" class="demo-persona-btn" title="Login sebagai Supplier Saprotan">
        <svg class="icon-svg" viewBox="0 0 24 24" style="color:#2563eb;">
          <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <div style="flex:1; overflow:hidden;">
          <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Supplier</div>
          <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">CV Tani Makmur</div>
        </div>
      </a>

      <!-- Buyer -->
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=demoLogin&role=buyer" class="demo-persona-btn" title="Login sebagai Pembeli Komoditas">
        <svg class="icon-svg" viewBox="0 0 24 24" style="color:#d97706;">
          <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
        </svg>
        <div style="flex:1; overflow:hidden;">
          <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Buyer</div>
          <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Resto Segar Nusantara</div>
        </div>
      </a>

      <!-- Admin -->
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=demoLogin&role=admin" class="demo-persona-btn" title="Login sebagai Administrator">
        <svg class="icon-svg" viewBox="0 0 24 24" style="color:#7c3aed;">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        <div style="flex:1; overflow:hidden;">
          <div style="font-weight:700; font-size:0.8rem; color:var(--slate-800);">Admin</div>
          <div style="font-size:0.68rem; color:var(--slate-500); white-space:nowrap; text-overflow:ellipsis; overflow:hidden;">Pengawas Sistem</div>
        </div>
      </a>
    </div>

    <!-- Footer Register Link -->
    <div style="margin-top:2rem; text-align:center; font-size:0.85rem; color:var(--slate-600); border-top:1px solid var(--slate-100); padding-top:1.25rem;">
      Belum memiliki akun AgriLink? 
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=register" style="font-weight:700; color:var(--primary-700);">Daftar Sekarang &rarr;</a>
    </div>

  </div>
</div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>
