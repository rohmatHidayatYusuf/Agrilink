<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Manajemen Pesanan & Transaksi Terpadu</h2>
    <p>Pantau penjualan hasil tani ke pembeli dan pelacakan pengadaan saprotan dari supplier dalam alur 4-tahap PRD.</p>
  </div>
</div>

<!-- TABEL 1: Pesanan Penjualan Hasil Tani Masuk (Petani sebagai Seller) -->
<div class="card-panel" style="margin-bottom:2rem;">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
    <div>
      <span class="card-panel-title">Pesanan Masuk Hasil Tani (Dari Pembeli)</span>
      <div style="font-size:0.75rem; color:var(--slate-500);">Permintaan pembelian hasil panen komoditas Anda.</div>
    </div>
    <span class="badge badge-success"><?= count($incomingSalesOrders) ?> Transaksi</span>
  </div>

  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($incomingSalesOrders)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No. Pesanan</th>
              <th>Pembeli / Alamat</th>
              <th>Item Komoditas (Snapshot)</th>
              <th>Total Tagihan</th>
              <th>Status Transaksi</th>
              <th>Aksi Update</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($incomingSalesOrders as $so): ?>
              <tr>
                <td>
                  <div style="font-weight:800; color:var(--slate-900);"><?= esc($so['order_number']) ?></div>
                  <div style="font-size:0.7rem; color:var(--slate-400);"><?= date('d M Y H:i', strtotime($so['created_at'])) ?></div>
                </td>
                <td>
                  <div style="font-weight:700; color:var(--slate-800);"><?= esc($so['buyer_name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc($so['delivery_address'] ?? $so['buyer_address']) ?></div>
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
                  <div style="font-size:0.7rem; color:var(--slate-400);">Termasuk ongkir: Rp <?= number_format($so['shipping_cost'], 0, ',', '.') ?></div>
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
                    class="btn btn-secondary btn-sm" 
                    onclick="openStatusModal('sales', <?= $so['id'] ?>, '<?= esc($so['order_number']) ?>', '<?= esc($so['status']) ?>')"
                  >
                    Ubah Status &rarr;
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">Belum ada pesanan komoditas masuk dari pembeli.</div>
    <?php endif; ?>
  </div>
</div>

<!-- TABEL 2: Pesanan Pengadaan Saprotan Keluar (Petani sebagai Buyer) -->
<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
    <div>
      <span class="card-panel-title">Pengadaan Saprotan Saya (Ke Supplier)</span>
      <div style="font-size:0.75rem; color:var(--slate-500);">Riwayat pemesanan benih, pupuk, dan alat pertanian Anda.</div>
    </div>
    <span class="badge badge-primary"><?= count($outgoingSupplyOrders) ?> Pengadaan</span>
  </div>

  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($outgoingSupplyOrders)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No. Pesanan</th>
              <th>Supplier</th>
              <th>Rincian Produk (Snapshot)</th>
              <th>Total Biaya</th>
              <th>Status Pengadaan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($outgoingSupplyOrders as $sup): ?>
              <tr>
                <td>
                  <div style="font-weight:800; color:var(--slate-900);"><?= esc($sup['order_number']) ?></div>
                  <div style="font-size:0.7rem; color:var(--slate-400);"><?= date('d M Y H:i', strtotime($sup['created_at'])) ?></div>
                </td>
                <td>
                  <div style="font-weight:700; color:var(--slate-800);"><?= esc($sup['supplier_name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);">Hubungi: <?= esc($sup['supplier_phone']) ?></div>
                </td>
                <td>
                  <?php if (!empty($sup['items'])): ?>
                    <?php foreach ($sup['items'] as $item): ?>
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
                    Rp <?= number_format($sup['total_amount'], 0, ',', '.') ?>
                  </div>
                  <div style="font-size:0.7rem; color:var(--slate-400);">Ongkir flat: Rp <?= number_format($sup['shipping_cost'], 0, ',', '.') ?></div>
                </td>
                <td>
                  <?php 
                    $badgeClass = 'badge-secondary';
                    if ($sup['status'] === 'menunggu_konfirmasi') $badgeClass = 'badge-warning';
                    elseif ($sup['status'] === 'dikonfirmasi') $badgeClass = 'badge-info';
                    elseif ($sup['status'] === 'diproses') $badgeClass = 'badge-primary';
                    elseif ($sup['status'] === 'selesai') $badgeClass = 'badge-success';
                    elseif (in_array($sup['status'], ['dibatalkan', 'ditolak'])) $badgeClass = 'badge-danger';
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= strtoupper(str_replace('_', ' ', $sup['status'])) ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">
        Belum ada pesanan pengadaan saprotan. Buka <a href="<?= base_url('saprotan') ?>" style="color:var(--primary-700); font-weight:700;">Marketplace Saprotan</a> untuk memesan benih & pupuk.
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- MODAL: Update Status Pesanan (Alur 4-Tahap PRD Bab 8.1) -->
<div class="modal-box-overlay" id="statusModal" style="display:none;">
  <div class="modal-box-card" style="max-width:480px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Pembaruan Tahap Status Transaksi</h3>
      <button type="button" class="modal-close-btn" onclick="closeStatusModal()">&times;</button>
    </div>
    <form action="<?= base_url('pesanan/update-status') ?>" method="POST">
      <?= csrf_field() ?>
      <input type="hidden" name="order_type" id="mOrderType">
      <input type="hidden" name="order_id" id="mOrderId">
      <div class="modal-box-body">
        
        <div style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:0.85rem; margin-bottom:1rem;">
          <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Nomor Pesanan:</div>
          <div id="mOrderNumber" style="font-size:1.1rem; font-weight:800; color:var(--slate-900);"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Pilih Tahap Status Baru <span class="required">*</span></label>
          <select class="form-control" name="new_status" id="mStatusSelect" required>
            <option value="menunggu_konfirmasi">1. Menunggu Konfirmasi</option>
            <option value="dikonfirmasi">2. Dikonfirmasi (Pesanan Diterima)</option>
            <option value="diproses">3. Diproses / Disiapkan / Dikirim</option>
            <option value="selesai">4. Selesai (Barang Tiba & Transaksi Tuntas)</option>
            <option value="ditolak">Ditolak</option>
            <option value="dibatalkan">Dibatalkan</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Perubahan (Tercatat di Jejak Status)</label>
          <input type="text" class="form-control" name="notes" placeholder="contoh: Komoditas telah dikemas dan dikirim">
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Status Transaksi</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openStatusModal(type, id, number, currentStatus) {
    document.getElementById('mOrderType').value = type;
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

