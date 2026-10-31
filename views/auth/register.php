<?php require_once ROOT_PATH . '/views/layout/header.php'; ?>

<div class="auth-page-wrapper">
  <div class="auth-card auth-card-wide">
    
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
      <h1>Buat Akun Baru</h1>
      <p>Pilih peran Anda dan bergabung dalam ekosistem digital pertanian terpadu.</p>
    </div>

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

    <!-- Form Registrasi -->
    <form action="<?= BASE_URL ?>/index.php?c=auth&a=register" method="POST" autocomplete="on">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Pemilihan Peran (Role) -->
      <div class="form-group">
        <label class="form-label">Pilih Peran Utama Anda di AgriLink</label>
        
        <div class="role-grid">
          <!-- Opsi Petani -->
          <div class="role-option">
            <input 
              type="radio" 
              name="role" 
              id="role_petani" 
              value="petani" 
              <?= ($old['role'] ?? 'petani') === 'petani' ? 'checked' : '' ?>
            >
            <label for="role_petani" class="role-card-label">
              <svg class="icon-svg" viewBox="0 0 24 24" style="color:var(--primary-700); width:24px; height:24px;">
                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
              </svg>
              <span class="role-title">Petani</span>
              <span class="role-desc">Produsen & Penjual Hasil Panen</span>
            </label>
          </div>

          <!-- Opsi Supplier -->
          <div class="role-option">
            <input 
              type="radio" 
              name="role" 
              id="role_supplier" 
              value="supplier" 
              <?= ($old['role'] ?? '') === 'supplier' ? 'checked' : '' ?>
            >
            <label for="role_supplier" class="role-card-label">
              <svg class="icon-svg" viewBox="0 0 24 24" style="color:#2563eb; width:24px; height:24px;">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
              <span class="role-title">Supplier</span>
              <span class="role-desc">Penyedia Pupuk, Bibit & Alat</span>
            </label>
          </div>

          <!-- Opsi Buyer -->
          <div class="role-option">
            <input 
              type="radio" 
              name="role" 
              id="role_buyer" 
              value="buyer" 
              <?= ($old['role'] ?? '') === 'buyer' ? 'checked' : '' ?>
            >
            <label for="role_buyer" class="role-card-label">
              <svg class="icon-svg" viewBox="0 0 24 24" style="color:#d97706; width:24px; height:24px;">
                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
              </svg>
              <span class="role-title">Buyer</span>
              <span class="role-desc">Resto, Grosir & Konsumen</span>
            </label>
          </div>
        </div>

        <?php if (!empty($errors['role'])): ?>
          <span class="invalid-feedback"><?= htmlspecialchars($errors['role']) ?></span>
        <?php endif; ?>
      </div>

      <!-- Baris: Nama Lengkap & Username -->
      <div class="form-row">
        <div class="form-group">
          <label for="nama_lengkap" class="form-label">Nama Lengkap / Nama Usaha</label>
          <div class="input-wrapper has-icon">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
            </span>
            <input 
              type="text" 
              id="nama_lengkap" 
              name="nama_lengkap" 
              class="form-control <?= !empty($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" 
              placeholder="Contoh: Pak Budi Santoso"
              value="<?= htmlspecialchars($old['nama_lengkap'] ?? '') ?>"
              required
            >
          </div>
          <?php if (!empty($errors['nama_lengkap'])): ?>
            <span class="invalid-feedback"><?= htmlspecialchars($errors['nama_lengkap']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="username" class="form-label">Username Akun</label>
          <div class="input-wrapper has-icon">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4"></circle>
                <path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path>
              </svg>
            </span>
            <input 
              type="text" 
              id="username" 
              name="username" 
              class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" 
              placeholder="huruf_kecil_tanpa_spasi"
              value="<?= htmlspecialchars($old['username'] ?? '') ?>"
              required
            >
          </div>
          <?php if (!empty($errors['username'])): ?>
            <span class="invalid-feedback"><?= htmlspecialchars($errors['username']) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Baris: Email & Telepon -->
      <div class="form-row">
        <div class="form-group">
          <label for="email" class="form-label">Alamat Email</label>
          <div class="input-wrapper has-icon">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
            </span>
            <input 
              type="email" 
              id="email" 
              name="email" 
              class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
              placeholder="nama@domain.com"
              value="<?= htmlspecialchars($old['email'] ?? '') ?>"
              required
            >
          </div>
          <?php if (!empty($errors['email'])): ?>
            <span class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="telepon" class="form-label">Nomor WhatsApp / HP</label>
          <div class="input-wrapper has-icon">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
              </svg>
            </span>
            <input 
              type="tel" 
              id="telepon" 
              name="telepon" 
              class="form-control <?= !empty($errors['telepon']) ? 'is-invalid' : '' ?>" 
              placeholder="08123456789"
              value="<?= htmlspecialchars($old['telepon'] ?? '') ?>"
            >
          </div>
          <?php if (!empty($errors['telepon'])): ?>
            <span class="invalid-feedback"><?= htmlspecialchars($errors['telepon']) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Baris: Kata Sandi & Konfirmasi -->
      <div class="form-row">
        <div class="form-group">
          <label for="reg_password" class="form-label">Kata Sandi (Min. 6 Karakter)</label>
          <div class="input-wrapper has-icon has-toggle">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
              </svg>
            </span>
            <input 
              type="password" 
              id="reg_password" 
              name="password" 
              class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" 
              placeholder="Buat kata sandi"
              required
            >
            <button type="button" class="password-toggle-btn" data-target="reg_password" title="Lihat/Sembunyikan Sandi">
              <svg class="icon-svg icon-eye" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
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

        <div class="form-group">
          <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi</label>
          <div class="input-wrapper has-icon has-toggle">
            <span class="input-icon">
              <svg class="icon-svg" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              </svg>
            </span>
            <input 
              type="password" 
              id="confirm_password" 
              name="confirm_password" 
              class="form-control <?= !empty($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
              placeholder="Ulangi kata sandi"
              required
            >
            <button type="button" class="password-toggle-btn" data-target="confirm_password" title="Lihat/Sembunyikan Sandi">
              <svg class="icon-svg icon-eye" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg class="icon-svg icon-eye-off" viewBox="0 0 24 24" style="display:none;">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
          <?php if (!empty($errors['confirm_password'])): ?>
            <span class="invalid-feedback"><?= htmlspecialchars($errors['confirm_password']) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Alamat / Wilayah Operasional -->
      <div class="form-group">
        <label for="alamat" class="form-label">Alamat / Lokasi Operasional (Opsional)</label>
        <input 
          type="text" 
          id="alamat" 
          name="alamat" 
          class="form-control" 
          placeholder="Contoh: Desa Sukamaju, Kec. Cisarua, Bogor"
          value="<?= htmlspecialchars($old['alamat'] ?? '') ?>"
        >
      </div>

      <!-- Syarat & Ketentuan -->
      <div class="form-group" style="margin-top:0.5rem; margin-bottom:1.5rem;">
        <label style="display:flex; align-items:flex-start; gap:0.5rem; cursor:pointer; font-size:0.82rem; color:var(--slate-600);">
          <input 
            type="checkbox" 
            name="agree_terms" 
            value="1" 
            style="accent-color:var(--primary-700); cursor:pointer; margin-top:0.2rem;" 
            required
          >
          <span>Saya menyetujui seluruh <a href="#" style="color:var(--primary-700); font-weight:600;">Syarat, Ketentuan & Kebijakan Privasi</a> platform AgriLink.</span>
        </label>
        <?php if (!empty($errors['agree_terms'])): ?>
          <span class="invalid-feedback"><?= htmlspecialchars($errors['agree_terms']) ?></span>
        <?php endif; ?>
      </div>

      <!-- Tombol Submit -->
      <button type="submit" class="btn btn-primary btn-block" style="padding:0.8rem 1.25rem;">
        <svg class="icon-svg" viewBox="0 0 24 24">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="8.5" cy="7" r="4"></circle>
          <line x1="20" y1="8" x2="20" y2="14"></line>
          <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
        <span>Daftar Akun Baru Sekarang</span>
      </button>
    </form>

    <!-- Footer Login Link -->
    <div style="margin-top:2rem; text-align:center; font-size:0.85rem; color:var(--slate-600); border-top:1px solid var(--slate-100); padding-top:1.25rem;">
      Sudah memiliki akun AgriLink? 
      <a href="<?= BASE_URL ?>/index.php?c=auth&a=login" style="font-weight:700; color:var(--primary-700);">Masuk ke Akun Anda &rarr;</a>
    </div>

  </div>
</div>

<?php require_once ROOT_PATH . '/views/layout/footer.php'; ?>
