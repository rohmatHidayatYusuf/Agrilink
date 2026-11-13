<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Manajemen Pengguna Sistem (Admin)</h2>
    <p>Pengawasan akun seluruh aktor ekosistem: Petani, Supplier, Buyer, dan Administrator sesuai ketentuan PRD Bab 6.7.</p>
  </div>
</div>

<!-- Filter Bar -->
<div class="card-panel" style="margin-bottom:1.5rem; padding:1.25rem;">
  <form action="<?= base_url('admin/users') ?>" method="GET" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center;">
    <div style="flex:2; min-width:240px;">
      <input type="text" class="form-control" name="cari" value="<?= esc($cari ?? '') ?>" placeholder="Cari nama pengguna, username, email...">
    </div>
    <div style="flex:1; min-width:160px;">
      <select class="form-control" name="role" onchange="this.form.submit()">
        <option value="all" <?= ($role ?? '') === 'all' ? 'selected' : '' ?>>Semua Peran (Role)</option>
        <option value="petani" <?= ($role ?? '') === 'petani' ? 'selected' : '' ?>>Petani</option>
        <option value="supplier" <?= ($role ?? '') === 'supplier' ? 'selected' : '' ?>>Supplier</option>
        <option value="buyer" <?= ($role ?? '') === 'buyer' ? 'selected' : '' ?>>Buyer</option>
        <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Administrator</option>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary">Filter</button>
  </form>
</div>

<!-- User Table -->
<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Daftar Akun Pengguna Terdaftar</span>
    <span class="badge badge-primary"><?= count($users) ?> Akun</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($users)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Pengguna</th>
              <th>Email & Telepon</th>
              <th>Peran Utama</th>
              <th>Status Akun</th>
              <th>Terdaftar</th>
              <th>Aksi Kontrol</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><strong>#<?= $u['id'] ?></strong></td>
                <td>
                  <div style="font-weight:700; color:var(--slate-900);"><?= esc($u['nama_lengkap']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-400);">@<?= esc($u['username']) ?></div>
                </td>
                <td>
                  <div><?= esc($u['email']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc($u['telepon'] ?? '-') ?></div>
                </td>
                <td>
                  <span class="badge <?= $u['role'] === 'admin' ? 'badge-primary' : ($u['role'] === 'petani' ? 'badge-success' : 'badge-info') ?>">
                    <?= strtoupper(esc($u['role'])) ?>
                  </span>
                </td>
                <td>
                  <?php if ($u['status'] === 'active'): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php elseif ($u['status'] === 'suspended'): ?>
                    <span class="badge badge-danger">Dinonaktifkan</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($u['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td>
                  <?php if ($u['role'] !== 'admin'): ?>
                    <form action="<?= base_url('admin/users/toggle/' . $u['id']) ?>" method="POST" style="display:inline;" onsubmit="return confirm('Ubah status akses pengguna ini?')">
                      <?= csrf_field() ?>
                      <?php if ($u['status'] === 'active'): ?>
                        <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">
                          Nonaktifkan
                        </button>
                      <?php else: ?>
                        <button type="submit" class="btn btn-primary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">
                          Aktifkan Kembali
                        </button>
                      <?php endif; ?>
                    </form>
                  <?php else: ?>
                    <span style="font-size:0.75rem; color:var(--slate-400);">Root Admin</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">Tidak ada pengguna yang memenuhi filter.</div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

