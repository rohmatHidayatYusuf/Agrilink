<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle ?? 'Daftar Akun Baru — AgriLink') ?></title>
  <meta name="description" content="Pendaftaran akun platform terintegrasi AgriLink.">
  
  <!-- Fonts -->
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
      padding: 2.5rem 1rem;
    }
    .auth-card {
      background: #ffffff;
      border: 1px solid var(--slate-200);
      border-radius: var(--radius-xl);
      max-width: 620px;
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
    .role-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }
    .role-option {
      border: 2px solid var(--slate-200);
      border-radius: var(--radius-lg);
      padding: 1rem 0.75rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      background: #ffffff;
    }
    .role-option:hover {
      border-color: var(--primary-500);
      background: var(--primary-50);
    }
    .role-option input[type="radio"] {
      display: none;
    }
    .role-option.selected {
      border-color: var(--primary-600);
      background: #ecfdf5;
    }
    .role-title {
      font-weight: 700;
      font-size: 0.9rem;
      color: var(--slate-800);
      margin-top: 0.35rem;
    }
    .role-desc {
      font-size: 0.72rem;
      color: var(--slate-500);
      margin-top: 0.2rem;
    }
  </style>
</head>
<body>

<div class="auth-page-wrapper">
  <div class="auth-card">
    
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
      <h1>Buat Akun Baru</h1>
      <p>Pilih peran Anda dan bergabung dalam ekosistem digital pertanian terpadu.</p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <svg class="icon-svg" viewBox="0 0 24 24" style="flex-shrink:0;">
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="8" x2="12" y2="12"></line>
          <line x1="12" y1="16" x2="12.01" y2="16"></line>
        </svg>
        <div>
          <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach (session()->getFlashdata('errors') as $err): ?>
              <li><?= esc($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="POST" id="regForm">
      <?= csrf_field() ?>

      <!-- Pemilihan Peran -->
      <div class="form-group">
        <label class="form-label">Pilih Peran Utama Anda di AgriLink <span class="required">*</span></label>
        <div class="role-grid">
          
          <label class="role-option selected" id="opt_petani">
            <input type="radio" name="role" value="petani" checked onchange="updateRoleUi('petani')">
            <svg class="icon-svg" viewBox="0 0 24 24" style="width:28px; height:28px; color:#16a34a;"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/></svg>
            <div class="role-title">Petani</div>
            <div class="role-desc">Produsen & Penjual</div>
          </label>

          <label class="role-option" id="opt_supplier">
            <input type="radio" name="role" value="supplier" onchange="updateRoleUi('supplier')">
            <svg class="icon-svg" viewBox="0 0 24 24" style="width:28px; height:28px; color:#2563eb;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <div class="role-title">Supplier</div>
            <div class="role-desc">Penyedia Saprotan</div>
          </label>

          <label class="role-option" id="opt_buyer">
            <input type="radio" name="role" value="buyer" onchange="updateRoleUi('buyer')">
            <svg class="icon-svg" viewBox="0 0 24 24" style="width:28px; height:28px; color:#d97706;"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/></svg>
            <div class="role-title">Buyer</div>
            <div class="role-desc">Pembeli Komoditas</div>
          </label>

        </div>
      </div>

      <!-- Data Akun -->
      <div class="grid-2col" style="gap:1rem;">
        <div class="form-group">
          <label class="form-label" for="nama_lengkap">Nama Lengkap / Instansi <span class="required">*</span></label>
          <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= esc(old('nama_lengkap')) ?>" placeholder="contoh: Pak Budi Santoso" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="username">Username Akun <span class="required">*</span></label>
          <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" placeholder="contoh: budi_santoso" required>
        </div>
      </div>

      <div class="grid-2col" style="gap:1rem;">
        <div class="form-group">
          <label class="form-label" for="email">Alamat Email <span class="required">*</span></label>
          <input type="email" class="form-control" id="email" name="email" value="<?= esc(old('email')) ?>" placeholder="nama@email.com" required>
        </div>

        <div class="form-group">
          <label class="form-label" for="telepon">No. Telepon / WhatsApp <span class="required">*</span></label>
          <input type="text" class="form-control" id="telepon" name="telepon" value="<?= esc(old('telepon')) ?>" placeholder="081234567890" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Kata Sandi (Password) <span class="required">*</span></label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
      </div>

      <div class="form-group">
        <label class="form-label" for="alamat">Alamat Domisili / Lokasi Operasional <span class="required">*</span></label>
        <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Nama jalan, desa/kelurahan, kecamatan, kabupaten/kota..." required><?= esc(old('alamat')) ?></textarea>
      </div>

      <!-- Input Khusus Petani: Luas Lahan Awal -->
      <div id="petaniExtraBox" style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1rem; margin-bottom:1.25rem;">
        <div style="font-size:0.85rem; font-weight:700; color:var(--slate-800); margin-bottom:0.5rem;">Informasi Lahan Pertama Petani (Opsional)</div>
        <div class="grid-2col" style="gap:0.75rem;">
          <div>
            <label class="form-label" style="font-size:0.78rem;">Nama Lahan:</label>
            <input type="text" class="form-control" name="initial_farm_name" placeholder="contoh: Lahan Blok A">
          </div>
          <div>
            <label class="form-label" style="font-size:0.78rem;">Luas Lahan (Ha):</label>
            <input type="number" step="0.1" min="0" class="form-control" name="initial_farm_area" placeholder="contoh: 1.5">
          </div>
        </div>
      </div>

      <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary" style="width:100%; padding:0.8rem; font-size:0.95rem;">
          Daftarkan Akun & Mulai Akses MVP &rarr;
        </button>
      </div>
    </form>

    <div style="margin-top:1.5rem; text-align:center; font-size:0.85rem; color:var(--slate-600); border-top:1px solid var(--slate-100); padding-top:1.25rem;">
      Sudah memiliki akun? 
      <a href="<?= base_url('login') ?>" style="font-weight:700; color:var(--primary-700);">Masuk di Sini &rarr;</a>
    </div>

  </div>
</div>

<script>
  function updateRoleUi(role) {
    document.querySelectorAll('.role-option').forEach(el => el.classList.remove('selected'));
    const selected = document.getElementById('opt_' + role);
    if (selected) selected.classList.add('selected');

    const extraBox = document.getElementById('petaniExtraBox');
    if (extraBox) {
      extraBox.style.display = (role === 'petani') ? 'block' : 'none';
    }
  }
</script>

</body>
</html>

