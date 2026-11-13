<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Riwayat Pesanan Komoditas Hasil Tani</h2>
    <p>Pantau perkembangan pesanan bahan pangan dan komoditas segar langsung dari petani mitra Anda.</p>
  </div>
  <a href="<?= base_url('buyer/katalog') ?>" class="btn btn-primary" style="text-decoration:none;">
    <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
    Belanja Komoditas Lagi
  </a>
</div>

<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Daftar Transaksi Pembelian Saya</span>
    <span class="badge badge-success"><?= count($orders) ?> Pesanan</span>
  </div>

  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($orders)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>No. Pesanan</th>
              <th>Petani Penjual</th>
              <th>Komoditas (Snapshot)</th>
              <th>Total Biaya</th>
              <th>Status Pemesanan</th>
              <th>Konfirmasi Terima</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $bo): ?>
              <tr>
                <td>
                  <div style="font-weight:800; color:var(--slate-900);"><?= esc($bo['order_number']) ?></div>
                  <div style="font-size:0.7rem; color:var(--slate-400);"><?= date('d M Y H:i', strtotime($bo['created_at'])) ?></div>
                </td>
                <td>
                  <div style="font-weight:700; color:var(--slate-800);"><?= esc($bo['seller_name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);">Kontak: <?= esc($bo['seller_phone']) ?></div>
                </td>
                <td>
                  <?php if (!empty($bo['items'])): ?>
                    <?php foreach ($bo['items'] as $item): ?>
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
                    Rp <?= number_format($bo['total_amount'], 0, ',', '.') ?>
                  </div>
                  <div style="font-size:0.7rem; color:var(--slate-400);">Termasuk ongkir flat: Rp <?= number_format($bo['shipping_cost'], 0, ',', '.') ?></div>
                </td>
                <td>
                  <?php 
                    $badgeClass = 'badge-secondary';
                    if ($bo['status'] === 'menunggu_konfirmasi') $badgeClass = 'badge-warning';
                    elseif ($bo['status'] === 'dikonfirmasi') $badgeClass = 'badge-info';
                    elseif ($bo['status'] === 'diproses') $badgeClass = 'badge-primary';
                    elseif ($bo['status'] === 'selesai') $badgeClass = 'badge-success';
                    elseif (in_array($bo['status'], ['dibatalkan', 'ditolak'])) $badgeClass = 'badge-danger';
                  ?>
                  <span class="badge <?= $badgeClass ?>"><?= strtoupper(str_replace('_', ' ', $bo['status'])) ?></span>
                </td>
                <td>
                  <?php if ($bo['status'] === 'diproses'): ?>
                    <form action="<?= base_url('buyer/pesanan/update-status') ?>" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang komoditas telah tiba dan diterima dengan baik?')">
                      <?= csrf_field() ?>
                      <input type="hidden" name="order_type" value="sales">
                      <input type="hidden" name="order_id" value="<?= $bo['id'] ?>">
                      <input type="hidden" name="new_status" value="selesai">
                      <input type="hidden" name="notes" value="Barang telah diterima oleh pembeli. Transaksi selesai.">
                      <button type="submit" class="btn btn-primary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">
                        Konfirmasi Diterima &check;
                      </button>
                    </form>
                  <?php elseif ($bo['status'] === 'selesai'): ?>
                    <span style="font-size:0.8rem; font-weight:700; color:var(--primary-700);">&check; Transaksi Tuntas</span>
                  <?php else: ?>
                    <span style="font-size:0.75rem; color:var(--slate-400);">Menunggu Pengiriman</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">
        Belum ada riwayat pesanan komoditas. Mulai berbelanja di <a href="<?= base_url('buyer/katalog') ?>" style="color:var(--primary-700); font-weight:700;">Katalog Pasar Hasil Tani</a>.
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

