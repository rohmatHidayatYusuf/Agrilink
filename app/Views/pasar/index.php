<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Pasar Hasil Tani — Etalase Komoditas Panen</h2>
    <p>Pusat perdagangan komoditas segar langsung dari petani produsen kepada restoran, tengkulak, dan pembeli akhir.</p>
  </div>
  <a href="<?= base_url('panen') ?>" class="btn btn-primary" style="text-decoration:none;">
    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    + Terbitkan dari Catatan Panen
  </a>
</div>

<!-- Penawaran Listing Saya (Petani yang Login) -->
<div class="card-panel" style="margin-bottom:1.75rem;">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Penawaran Komoditas Milik Saya</span>
    <span class="badge badge-success"><?= count($myListings) ?> Listing</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($myListings)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>Judul Listing</th>
              <th>Harga Satuan (Rp)</th>
              <th>Stok Siap Jual</th>
              <th>Min. Order</th>
              <th>Status Listing</th>
              <th>Tanggal Diterbitkan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($myListings as $ml): ?>
              <tr>
                <td>
                  <div style="font-weight:700; color:var(--slate-900);"><?= esc($ml['title']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc(substr($ml['description'] ?? '', 0, 75)) ?>...</div>
                </td>
                <td style="font-weight:700; color:var(--primary-700);">
                  Rp <?= number_format($ml['price_per_unit'], 0, ',', '.') ?> <span style="font-size:0.75rem; font-weight:400; color:var(--slate-500);">/ <?= esc($ml['unit']) ?></span>
                </td>
                <td>
                  <strong style="color:<?= $ml['quantity_available'] > 0 ? 'var(--slate-800)' : 'var(--danger-600)' ?>;">
                    <?= number_format($ml['quantity_available'], 0, ',', '.') ?> <?= esc($ml['unit']) ?>
                  </strong>
                </td>
                <td><?= number_format($ml['min_order'], 0, ',', '.') ?> <?= esc($ml['unit']) ?></td>
                <td>
                  <?php if ($ml['status'] === 'active'): ?>
                    <span class="badge badge-success">Aktif di Pasar</span>
                  <?php elseif ($ml['status'] === 'sold_out'): ?>
                    <span class="badge badge-secondary">Habis Terjual</span>
                  <?php elseif ($ml['status'] === 'moderated'): ?>
                    <span class="badge badge-danger">Dimoderasi</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($ml['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td><?= date('d M Y H:i', strtotime($ml['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">
        Anda belum menerbitkan penawaran komoditas. Buka menu <a href="<?= base_url('panen') ?>" style="color:var(--primary-700); font-weight:700;">Pencatatan Panen</a> dan klik "Jual ke Pasar".
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Seluruh Listing di Pasar Hasil Tani -->
<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
    <span class="card-panel-title">Semua Komoditas Aktif di Pasar AgriLink</span>
    <form action="<?= base_url('pasar') ?>" method="GET" style="display:flex; gap:0.5rem;">
      <input type="text" class="form-control form-control-sm" name="cari" value="<?= esc($cari ?? '') ?>" placeholder="Cari beras, cabai..." style="width:200px;">
      <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
    </form>
  </div>
  <div class="card-panel-body" style="padding:1.25rem;">
    <?php if (!empty($marketListings)): ?>
      <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.25rem;">
        <?php foreach ($marketListings as $listing): ?>
          <div style="border:1px solid var(--slate-200); border-radius:var(--radius-lg); padding:1.25rem; display:flex; flex-direction:column; justify-content:space-between; background:#fff;">
            <div>
              <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem;">
                <span class="badge badge-primary"><?= esc($listing['commodity']) ?></span>
                <span class="badge badge-success">Stok: <?= number_format($listing['quantity_available'], 0, ',', '.') ?> <?= esc($listing['unit']) ?></span>
              </div>
              <h4 style="font-size:1.05rem; font-weight:800; color:var(--slate-900); margin:0 0 0.35rem 0;">
                <?= esc($listing['title']) ?>
              </h4>
              <p style="font-size:0.8rem; color:var(--slate-600); margin-bottom:0.75rem; line-height:1.4;">
                <?= esc(substr($listing['description'] ?? '', 0, 100)) ?>...
              </p>
              <div style="font-size:0.75rem; color:var(--slate-500); margin-bottom:0.75rem;">
                Petani: <strong><?= esc($listing['seller_name']) ?></strong> &bull; <?= esc($listing['seller_address']) ?>
              </div>
            </div>

            <div style="border-top:1px solid var(--slate-100); padding-top:0.75rem; display:flex; justify-content:space-between; align-items:center;">
              <div>
                <div style="font-size:0.7rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Harga:</div>
                <div style="font-size:1.15rem; font-weight:800; color:var(--primary-700);">
                  Rp <?= number_format($listing['price_per_unit'], 0, ',', '.') ?> <span style="font-size:0.75rem; font-weight:400; color:var(--slate-500);">/ <?= esc($listing['unit']) ?></span>
                </div>
              </div>
              <span class="badge badge-info">Tersedia</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="padding:2.5rem; text-align:center; color:var(--slate-500);">
        Tidak ada komoditas hasil tani yang cocok dengan kata kunci pencarian.
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

