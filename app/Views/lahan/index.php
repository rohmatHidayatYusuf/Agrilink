<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Manajemen Profil Lahan & Rencana Tanam</h2>
    <p>Kelola data luas lahan pertanian dan susun siklus jadwal penanaman komoditas.</p>
  </div>
  <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
    <button type="button" class="btn btn-secondary" onclick="openModal('addFarmModal')">
      <svg class="icon-svg" viewBox="0 0 24 24"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/></svg>
      + Tambah Profil Lahan
    </button>
    <button type="button" class="btn btn-primary" onclick="openModal('addPlanModal')">
      <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      + Rencana Tanam Baru
    </button>
  </div>
</div>

<!-- KPI Luas Lahan -->
<div class="stats-overview-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Total Luas Lahan</div>
    <div class="kpi-figure-value"><?= number_format($totalArea, 2) ?> Ha</div>
    <div class="kpi-subtext-hint">Dari <?= count($farms) ?> lokasi profil lahan</div>
  </div>
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Luas Terpakai (Tanam)</div>
    <div class="kpi-figure-value" style="color:var(--primary-700);"><?= number_format($totalUsed, 2) ?> Ha</div>
    <div class="kpi-subtext-hint">Sedang aktif dalam siklus tanam</div>
  </div>
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Sisa Kapasitas Tersedia</div>
    <div class="kpi-figure-value" style="color:var(--slate-800);"><?= number_format($totalRemaining, 2) ?> Ha</div>
    <div class="kpi-subtext-hint">Siap dialokasikan rencana baru</div>
  </div>
</div>

<!-- Tabel Rencana Tanam Aktif -->
<div class="card-panel" style="margin-bottom:1.5rem;">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Daftar Rencana Penanaman Komoditas</span>
    <span class="badge badge-success"><?= count($plans) ?> Rencana</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($plans)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Komoditas</th>
              <th>Lokasi Lahan</th>
              <th>Luas Tanam</th>
              <th>Tanggal Tanam</th>
              <th>Perkiraan Panen</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($plans as $p): ?>
              <tr>
                <td style="font-weight:700; color:var(--slate-900);"><?= esc($p['commodity']) ?></td>
                <td><?= esc($p['farm_name']) ?></td>
                <td><?= number_format($p['planned_area'], 2) ?> Ha</td>
                <td><?= date('d M Y', strtotime($p['start_date'])) ?></td>
                <td><span class="badge badge-primary"><?= date('d M Y', strtotime($p['expected_harvest_date'])) ?></span></td>
                <td>
                  <?php if ($p['status'] === 'active'): ?>
                    <span class="badge badge-success">Masa Tanam Aktif</span>
                  <?php elseif ($p['status'] === 'harvested'): ?>
                    <span class="badge badge-info">Telah Dipanen</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($p['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="<?= base_url('estimasi') ?>" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;" title="Hitung Estimasi Modal">
                    Estimasi Modal &rarr;
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">
        Belum ada rencana tanam aktif. Klik tombol <strong>+ Rencana Tanam Baru</strong> di atas untuk memulai.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Tabel Profil Lahan -->
<div class="card-panel">
  <div class="card-panel-header">
    <span class="card-panel-title">Profil Master Lahan Petani</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($farms)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Nama Lahan</th>
              <th>Keterangan Lokasi</th>
              <th>Luas Total</th>
              <th>Terpakai</th>
              <th>Sisa Luas</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($farms as $f): ?>
              <?php $sisa = max(0.0, (float)$f['area_size'] - (float)$f['total_used_area']); ?>
              <tr>
                <td style="font-weight:700; color:var(--slate-900);"><?= esc($f['name']) ?></td>
                <td><?= esc($f['location']) ?></td>
                <td><?= number_format($f['area_size'], 2) ?> <?= esc($f['area_unit']) ?></td>
                <td><?= number_format($f['total_used_area'], 2) ?> <?= esc($f['area_unit']) ?></td>
                <td><strong style="color:var(--primary-700);"><?= number_format($sisa, 2) ?> <?= esc($f['area_unit']) ?></strong></td>
                <td><span class="badge badge-success"><?= strtoupper(esc($f['status'])) ?></span></td>
                <td>
                  <a href="<?= base_url('lahan/arsip/' . $f['id']) ?>" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;" onclick="return confirm('Arsipkan profil lahan ini? Catatan panen dan riwayat tetap aman.')">
                    Arsipkan
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">
        Belum ada profil lahan yang terdaftar. Tambahkan lahan pertama Anda sekarang.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL: Tambah Profil Lahan -->
<div class="modal-box-overlay" id="addFarmModal" style="display:none;">
  <div class="modal-box-card" style="max-width:500px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Tambah Profil Lahan Baru</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('addFarmModal')">&times;</button>
    </div>
    <form action="<?= base_url('lahan/simpan') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="modal-box-body">
        <div class="form-group">
          <label class="form-label">Nama Identitas Lahan <span class="required">*</span></label>
          <input type="text" class="form-control" name="name" placeholder="contoh: Lahan Blok C - Cisarua" required>
        </div>
        <div class="form-group">
          <label class="form-label">Luas Lahan <span class="required">*</span></label>
          <div style="display:flex; gap:0.5rem;">
            <input type="number" step="0.01" min="0.01" class="form-control" name="area_size" placeholder="contoh: 1.50" required style="flex:2;">
            <select class="form-control" name="area_unit" style="flex:1;">
              <option value="Ha">Hektar (Ha)</option>
              <option value="m2">m²</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Keterangan Lokasi & Akses Air <span class="required">*</span></label>
          <textarea class="form-control" name="location" rows="3" placeholder="contoh: Kampung Sindangrasa, irigasi teknis lancar, dekat jalan desa" required></textarea>
        </div>
      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addFarmModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Profil Lahan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Tambah Rencana Tanam -->
<div class="modal-box-overlay" id="addPlanModal" style="display:none;">
  <div class="modal-box-card" style="max-width:540px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Buat Rencana Penanaman Baru</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('addPlanModal')">&times;</button>
    </div>
    <form action="<?= base_url('lahan/rencana') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="modal-box-body">
        <div class="form-group">
          <label class="form-label">Pilih Lokasi Lahan <span class="required">*</span></label>
          <select class="form-control" name="farm_id" required>
            <option value="">-- Pilih Profil Lahan --</option>
            <?php foreach ($farms as $f): ?>
              <?php $sisa = max(0.0, (float)$f['area_size'] - (float)$f['total_used_area']); ?>
              <option value="<?= $f['id'] ?>">
                <?= esc($f['name']) ?> (Total: <?= $f['area_size'] ?> Ha | Sisa Tersedia: <?= number_format($sisa, 2) ?> Ha)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Varietas Komoditas <span class="required">*</span></label>
          <input type="text" class="form-control" name="commodity" placeholder="contoh: Padi Ciherang / Cabai Rawit Merah" required>
        </div>
        <div class="form-group">
          <label class="form-label">Luas Area Tanam (Ha) <span class="required">*</span></label>
          <input type="number" step="0.01" min="0.01" class="form-control" name="planned_area" placeholder="contoh: 0.50" required>
          <div class="form-hint">Tidak boleh melebihi sisa kapasitas lahan yang dipilih.</div>
        </div>
        <div class="grid-2col" style="gap:1rem;">
          <div class="form-group">
            <label class="form-label">Tanggal Mulai Tanam <span class="required">*</span></label>
            <input type="date" class="form-control" name="start_date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Perkiraan Panen <span class="required">*</span></label>
            <input type="date" class="form-control" name="expected_harvest_date" value="<?= date('Y-m-d', strtotime('+90 days')) ?>" required>
          </div>
        </div>
      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addPlanModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Rencana Tanam</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'flex';
  }
  function closeModal(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  }
</script>
<?= $this->endSection() ?>

