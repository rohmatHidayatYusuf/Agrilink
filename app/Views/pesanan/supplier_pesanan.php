<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Daftar Pesanan Saprotan Masuk</h2>
    <p>Kelola pesanan sarana produksi dari para petani, perbarui status pemenuhan barang, dan lacak penyelesaian transaksi.</p>
  </div>
</div>

<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Semua Pesanan Pengadaan Masuk</span>
    <span class="badge badge-primary"><?= count($orders) ?> Pesanan</span>
  </div>

  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($orders)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No. Pesanan</th>
              <th>Pemesan (Petani) / Alamat</th>
              <th>Item Saprotan</th>
              <th>Total Biaya</th>
              <th>Status Transaksi</th>
              <th>Aksi Update</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $so): ?>
              <tr>
                <td>
                  <div style="font-weight:800; color:var(--slate-900);"><?= esc($so['order_number']) ?></div>
                  <div style="font-size:0.7rem; color:var(--slate-400);"><?= date('d M Y H:i', strtotime($so['created_at'])) ?></div>
                </td>
                <td>
                  <div style="font-weight:700; color:var(--slate-800);"><?= esc($so['buyer_name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc($so['shipping_address'] ?? $so['buyer_address']) ?></div>
                  <div style="font-size:0.72rem; color:var(--primary-700);">Telp: <?= esc($so['buyer_phone']) ?></div>
                </td>
                <td>
                  <?php if (!empty($so['items'])): ?>
                    <?php foreach ($so['items'] as $item): ?>
                      <div style="font-size:0.85rem; font-weight:600; color:var(--slate-800);">
                        <?= esc($item['product_name_snapshot']) ?>
                      </div>
                      <div style="font-size:0.75rem; color:var(--slate-500);">
                        <?= number_format($item['quantity'], 0, ',', '.') ?> <?= esc($item['unit']) ?> &times; Rp <?= number_format($item['unit_price_snapshot'], 0, ',', '.') ?>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </td>
                <td>
                  <div style="font-weight:800; font-size:1rem; color:var(--primary-700);">
                    Rp <?= number_format($so['total_amount'], 0, ',', '.') ?>
                  </div>
                </td>
                <td>
                  <?php 
                    $badgeClass = 'badge-secondary';
                    if ($so['status'] === 'menunggu_konfirmasi') $badgeClass = 'badge-warning';
                    elseif ($so['status'] === 'dikonfirmasi') $badgeClass = 'badge-info';
                    elseif ($so['status'] === 'diproses') $badgeClass = 'badge-primary';
                    elseif ($so['status'] === 'selesai') $badgeClass = 'badge-success';
                    elseif (in_array($so['status'], ['dibatalkan', 'ditolak'])) $badgeClass = 'badge-danger';
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= strtoupper(str_replace('_', ' ', $so['status'])) ?></span>
                </td>
                <td>
                  <button 
                    type="button" 
                    class="btn btn-primary btn-sm" 
                    onclick="openStatusModal('supply', <?= $so['id'] ?>, '<?= esc($so['order_number']) ?>', '<?= esc($so['status']) ?>')"
                  >
                    Proses Status &rarr;
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">Belum ada pesanan masuk dari petani.</div>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL: Update Status Pesanan -->
<div class="modal-box-overlay" id="statusModal" style="display:none;">
  <div class="modal-box-card" style="max-width:480px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Pembaruan Tahap Pemenuhan Pesanan</h3>
      <button type="button" class="modal-close-btn" onclick="closeStatusModal()">&times;</button>
    </div>
    <form action="<?= base_url('supplier/pesanan/update-status') ?>" method="POST">
      <?= csrf_field() ?>
      <input type="hidden" name="order_type" value="supply">
      <input type="hidden" name="order_id" id="mOrderId">
      <div class="modal-box-body">
        
        <div style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:0.85rem; margin-bottom:1rem;">
          <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Nomor Pesanan:</div>
          <div id="mOrderNumber" style="font-size:1.1rem; font-weight:800; color:var(--slate-900);"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Tahap Status Baru <span class="required">*</span></label>
          <select class="form-control" name="new_status" id="mStatusSelect" required>
            <option value="menunggu_konfirmasi">1. Menunggu Konfirmasi</option>
            <option value="dikonfirmasi">2. Dikonfirmasi (Stok Disiapkan)</option>
            <option value="diproses">3. Diproses / Dalam Pengiriman</option>
            <option value="selesai">4. Selesai (Diterima Petani)</option>
            <option value="ditolak">Ditolak</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Tambahan Supplier</label>
          <input type="text" class="form-control" name="notes" placeholder="contoh: Dikirim dengan kurir ekspedisi armada gudang">
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Status Pesanan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openStatusModal(type, id, number, currentStatus) {
    document.getElementById('mOrderId').value = id;
    document.getElementById('mOrderNumber').textContent = number;
    document.getElementById('mStatusSelect').value = currentStatus;
    document.getElementById('statusModal').style.display = 'flex';
  }

  function closeStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
  }
</script>
<?= $this->endSection() ?>

