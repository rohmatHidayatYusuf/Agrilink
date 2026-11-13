<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Pencatatan Hasil Panen Fisik</h2>
    <p>Dokumentasikan volume riil panen komoditas dari lahan tanam dan publikasikan penawaran langsung ke Pasar Hasil Tani.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="openModal('addHarvestModal')">
    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    + Catat Panen Baru
  </button>
</div>

<!-- KPI Panen -->
<div class="stats-overview-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Total Volume Panen Fisik</div>
    <div class="kpi-figure-value"><?= number_format($totalHarvestQty, 0, ',', '.') ?> kg</div>
    <div class="kpi-subtext-hint">Dari seluruh siklus musim tanam</div>
  </div>
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Dialokasikan / Terjual</div>
    <div class="kpi-figure-value" style="color:var(--primary-700);"><?= number_format($totalSoldQty, 0, ',', '.') ?> kg</div>
    <div class="kpi-subtext-hint">Telah dipublikasi ke pasar atau terjual</div>
  </div>
  <div class="stat-kpi-card">
    <div class="kpi-meta-title">Sisa Fisik Tersedia</div>
    <div class="kpi-figure-value" style="color:var(--slate-800);"><?= number_format($totalReadyQty, 0, ',', '.') ?> kg</div>
    <div class="kpi-subtext-hint">Siap diterbitkan menjadi penawaran</div>
  </div>
</div>

<!-- Tabel Catatan Panen -->
<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Riwayat Pencatatan Hasil Panen</span>
    <span class="badge badge-success"><?= count($records) ?> Riwayat</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($records)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Komoditas</th>
              <th>Lahan Sumber</th>
              <th>Tanggal Panen</th>
              <th>Volume Panen</th>
              <th>Dialokasikan</th>
              <th>Sisa Stok Fisik</th>
              <th>Mutu / Lokasi Simpan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($records as $r): ?>
              <?php $sisa = max(0.0, (float)$r['quantity'] - (float)$r['quantity_sold']); ?>
              <tr>
                <td style="font-weight:700; color:var(--slate-900);"><?= esc($r['commodity']) ?></td>
                <td><?= esc($r['farm_name']) ?></td>
                <td><?= date('d M Y', strtotime($r['harvest_date'])) ?></td>
                <td><strong><?= number_format($r['quantity'], 0, ',', '.') ?> <?= esc($r['unit']) ?></strong></td>
                <td><?= number_format($r['quantity_sold'], 0, ',', '.') ?> <?= esc($r['unit']) ?></td>
                <td>
                  <strong style="color:<?= $sisa > 0 ? 'var(--primary-700)' : 'var(--slate-400)' ?>;">
                    <?= number_format($sisa, 0, ',', '.') ?> <?= esc($r['unit']) ?>
                  </strong>
                </td>
                <td>
                  <div style="font-size:0.8rem; font-weight:600; color:var(--slate-800);"><?= esc($r['quality_notes']) ?></div>
                  <div style="font-size:0.7rem; color:var(--slate-500);"><?= esc($r['storage_location']) ?></div>
                </td>
                <td>
                  <?php if ($sisa > 0.01): ?>
                    <button 
                      type="button" 
                      class="btn btn-primary btn-sm" 
                      style="padding:0.25rem 0.5rem; font-size:0.75rem;" 
                      onclick="openPublishModal(<?= $r['id'] ?>, '<?= esc(addslashes($r['commodity'])) ?>', <?= $sisa ?>, '<?= esc($r['unit']) ?>')"
                    >
                      Jual ke Pasar &rarr;
                    </button>
                  <?php else: ?>
                    <span class="badge badge-secondary">Habis Terjual</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">
        Belum ada catatan panen. Catat panen pertama Anda melalui tombol <strong>+ Catat Panen Baru</strong>.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL: Catat Panen Baru -->
<div class="modal-box-overlay" id="addHarvestModal" style="display:none;">
  <div class="modal-box-card" style="max-width:540px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Form Catat Hasil Panen Fisik</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('addHarvestModal')">&times;</button>
    </div>
    <form action="<?= base_url('panen/simpan') ?>" method="POST">
      <?= csrf_field() ?>
      <div class="modal-box-body">
        
        <div class="form-group">
          <label class="form-label">Pilih Rencana Tanam Terkait <span class="required">*</span></label>
          <select class="form-control" name="farm_plan_id" required>
            <option value="">-- Pilih Rencana Tanam --</option>
            <?php foreach ($plans as $p): ?>
              <option value="<?= $p['id'] ?>">
                <?= esc($p['commodity']) ?> (<?= esc($p['farm_name']) ?> - <?= $p['planned_area'] ?> Ha)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="grid-2col" style="gap:1rem;">
          <div class="form-group">
            <label class="form-label">Tanggal Panen <span class="required">*</span></label>
            <input type="date" class="form-control" name="harvest_date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Volume Hasil Panen <span class="required">*</span></label>
            <div style="display:flex; gap:0.5rem;">
              <input type="number" step="0.1" min="0.1" class="form-control" name="quantity" placeholder="contoh: 500" required style="flex:2;">
              <input type="text" class="form-control" name="unit" value="kg" required style="flex:1;">
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Mutu / Grade Hasil Panen</label>
          <input type="text" class="form-control" name="quality_notes" placeholder="contoh: Grade Super, Kadar Air 14%, Segar Petik Pagi">
        </div>

        <div class="form-group">
          <label class="form-label">Lokasi Penyimpanan Gudang</label>
          <input type="text" class="form-control" name="storage_location" placeholder="contoh: Gudang Dingin Blok B Megamendung">
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addHarvestModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Catatan Panen</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: Terbitkan ke Pasar Hasil Tani -->
<div class="modal-box-overlay" id="publishModal" style="display:none;">
  <div class="modal-box-card" style="max-width:540px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Terbitkan Penawaran ke Pasar Hasil Tani</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('publishModal')">&times;</button>
    </div>
    <form action="<?= base_url('panen/jual') ?>" method="POST">
      <?= csrf_field() ?>
      <input type="hidden" name="harvest_record_id" id="pubRecordId">
      <div class="modal-box-body">
        
        <div style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:0.85rem; margin-bottom:1rem;">
          <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Sumber Panen:</div>
          <div id="pubCommodityName" style="font-size:1.05rem; font-weight:800; color:var(--slate-900);"></div>
          <div style="font-size:0.8rem; color:var(--slate-600); margin-top:0.2rem;">
            Maksimal kuantitas siap jual: <strong id="pubMaxQtyText" style="color:var(--primary-700);"></strong>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Judul Penawaran Listing <span class="required">*</span></label>
          <input type="text" class="form-control" name="title" id="pubTitle" placeholder="contoh: Cabai Rawit Merah Segar Petik Pagi" required>
        </div>

        <div class="grid-2col" style="gap:1rem;">
          <div class="form-group">
            <label class="form-label">Kuantitas Dijual (<span id="pubUnitText">kg</span>) <span class="required">*</span></label>
            <input type="number" step="0.1" min="0.1" class="form-control" name="quantity_available" id="pubQtyInput" required>
          </div>
          <div class="form-group">
            <label class="form-label">Harga Satuan (Rp) <span class="required">*</span></label>
            <input type="number" step="100" min="500" class="form-control" name="price_per_unit" placeholder="contoh: 38000" required>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Minimal Pemesanan Pembeli</label>
          <input type="number" step="0.1" min="0.1" class="form-control" name="min_order" value="1.0">
        </div>

        <div class="form-group">
          <label class="form-label">Deskripsi Penawaran Hasil Panen</label>
          <textarea class="form-control" name="description" rows="2" placeholder="Jelaskan kondisi komoditas, kesegaran, daya tahan..."></textarea>
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('publishModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Publikasikan ke Pasar Tani &rarr;</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openModal(id) { document.getElementById(id).style.display = 'flex'; }
  function closeModal(id) { document.getElementById(id).style.display = 'none'; }

  function openPublishModal(id, commodity, maxQty, unit) {
    document.getElementById('pubRecordId').value = id;
    document.getElementById('pubCommodityName').textContent = commodity;
    document.getElementById('pubTitle').value = commodity + ' Segar Pilihan';
    document.getElementById('pubMaxQtyText').textContent = maxQty.toLocaleString('id-ID') + ' ' + unit;
    document.getElementById('pubUnitText').textContent = unit;
    document.getElementById('pubQtyInput').value = maxQty;
    document.getElementById('pubQtyInput').max = maxQty;
    document.getElementById('publishModal').style.display = 'flex';
  }
</script>
<?= $this->endSection() ?>

