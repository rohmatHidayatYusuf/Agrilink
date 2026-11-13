<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Manajemen Katalog Produk Saprotan</h2>
    <p>Kelola daftar benih, pupuk, pestisida, dan alsintan yang Anda sediakan untuk para petani.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="openModal('addProductModal')">
    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    + Tambah Produk Baru
  </button>
</div>

<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Daftar Produk Aktif di Etalase</span>
    <span class="badge badge-success"><?= count($products) ?> Produk</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($products)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th>Harga Jual (Rp)</th>
              <th>Stok Tersedia</th>
              <th>Status</th>
              <th>Terakhir Diperbarui</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $p): ?>
              <tr>
                <td>
                  <div style="font-weight:700; color:var(--slate-900);"><?= esc($p['name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc(substr($p['description'] ?? '', 0, 75)) ?>...</div>
                </td>
                <td><span class="badge badge-primary"><?= esc($p['category']) ?></span></td>
                <td style="font-weight:700; color:var(--primary-700);">
                  Rp <?= number_format($p['price'], 0, ',', '.') ?> <span style="font-size:0.75rem; font-weight:400; color:var(--slate-500);">/ <?= esc($p['unit']) ?></span>
                </td>
                <td>
                  <strong style="color:<?= $p['stock'] > 10 ? 'var(--slate-800)' : 'var(--danger-600)' ?>;">
                    <?= number_format($p['stock'], 0, ',', '.') ?> <?= esc($p['unit']) ?>
                  </strong>
                </td>
                <td>
                  <?php if ($p['status'] === 'active'): ?>
                    <span class="badge badge-success">Aktif di Pasar</span>
                  <?php elseif ($p['status'] === 'moderated'): ?>
                    <span class="badge badge-danger">Dimoderasi</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($p['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y H:i', strtotime($p['updated_at'] ?? $p['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">
        Belum ada produk di katalog Anda. Klik <strong>+ Tambah Produk Baru</strong> untuk mulai menjual saprotan.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL: Tambah Produk Saprotan -->
<div class="modal-box-overlay" id="addProductModal" style="display:none;">
  <div class="modal-box-card" style="max-width:540px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Tambah Produk Saprotan Baru</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('addProductModal')">&times;</button>
    </div>
    <form action="<?= base_url('supplier/produk/simpan') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="modal-box-body">
        
        <div class="form-group">
          <label class="form-label">Nama Produk Saprotan <span class="required">*</span></label>
          <input type="text" class="form-control" name="name" placeholder="contoh: Benih Padi Ciherang Unggul 5kg" required>
        </div>

        <div class="grid-2col" style="gap:1rem;">
          <div class="form-group">
            <label class="form-label">Kategori <span class="required">*</span></label>
            <select class="form-control" name="category" required>
              <option value="Benih">Benih & Bibit</option>
              <option value="Pupuk">Pupuk</option>
              <option value="Pestisida">Pestisida</option>
              <option value="Alsintan">Peralatan / Alsintan</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">Satuan Penjualan <span class="required">*</span></label>
            <input type="text" class="form-control" name="unit" placeholder="contoh: zak 5kg, botol, karung" required>
          </div>
        </div>

        <div class="grid-2col" style="gap:1rem;">
          <div class="form-group">
            <label class="form-label">Harga Jual Satuan (Rp) <span class="required">*</span></label>
            <input type="number" step="500" min="500" class="form-control" name="price" placeholder="contoh: 85000" required>
          </div>

          <div class="form-group">
            <label class="form-label">Stok Awal <span class="required">*</span></label>
            <input type="number" step="1" min="1" class="form-control" name="stock" placeholder="contoh: 100" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Spesifikasi Produk</label>
          <textarea class="form-control" name="description" rows="3" placeholder="Keunggulan, izin edar balai benih, dosis aplikasi, dll..."></textarea>
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan ke Katalog</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openModal(id) { document.getElementById(id).style.display = 'flex'; }
  function closeModal(id) { document.getElementById(id).style.display = 'none'; }
</script>
<?= $this->endSection() ?>

