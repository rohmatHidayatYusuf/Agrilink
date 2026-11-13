<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Moderasi Konten & Listing Marketplace</h2>
    <p>Tinjau dan nonaktifkan penawaran saprotan atau hasil panen yang dilaporkan atau melanggar kebijakan sistem (PRD ADM-03 & ADM-04).</p>
  </div>
</div>

<!-- Moderasi Produk Saprotan -->
<div class="card-panel" style="margin-bottom:2rem;">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Katalog Produk Saprotan (Supplier)</span>
    <span class="badge badge-primary"><?= count($products) ?> Produk</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($products)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nama Produk Saprotan</th>
              <th>Supplier</th>
              <th>Harga & Stok</th>
              <th>Status Moderasi</th>
              <th>Aksi Moderasi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $pr): ?>
              <tr>
                <td><strong>#<?= $pr['id'] ?></strong></td>
                <td>
                  <div style="font-weight:700; color:var(--slate-900);"><?= esc($pr['name']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc($pr['category']) ?> &bull; <?= esc(substr($pr['description'] ?? '', 0, 60)) ?>...</div>
                </td>
                <td style="font-weight:600;"><?= esc($pr['supplier_name']) ?></td>
                <td>
                  <div>Rp <?= number_format($pr['price'], 0, ',', '.') ?> / <?= esc($pr['unit']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);">Stok: <?= number_format($pr['stock'], 0, ',', '.') ?></div>
                </td>
                <td>
                  <?php if ($pr['status'] === 'active'): ?>
                    <span class="badge badge-success">Aktif Normal</span>
                  <?php elseif ($pr['status'] === 'moderated'): ?>
                    <span class="badge badge-danger">Ditangguhkan</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($pr['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <form action="<?= base_url('admin/moderasi/toggle/saprotan/' . $pr['id']) ?>" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <?php if ($pr['status'] === 'active'): ?>
                      <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;" onclick="return confirm('Tangguhkan/sembunyikan produk saprotan ini?')">
                        Tangguhkan
                      </button>
                    <?php else: ?>
                      <button type="submit" class="btn btn-primary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">
                        Pulihkan
                      </button>
                    <?php endif; ?>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Moderasi Listing Hasil Panen -->
<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Listing Pasar Hasil Tani (Petani)</span>
    <span class="badge badge-success"><?= count($listings) ?> Listing</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($listings)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Judul Penawaran Hasil Panen</th>
              <th>Petani Penjual</th>
              <th>Harga & Kuantitas</th>
              <th>Status Moderasi</th>
              <th>Aksi Moderasi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($listings as $li): ?>
              <tr>
                <td><strong>#<?= $li['id'] ?></strong></td>
                <td>
                  <div style="font-weight:700; color:var(--slate-900);"><?= esc($li['title']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);"><?= esc(substr($li['description'] ?? '', 0, 60)) ?>...</div>
                </td>
                <td style="font-weight:600;"><?= esc($li['seller_name']) ?></td>
                <td>
                  <div>Rp <?= number_format($li['price_per_unit'], 0, ',', '.') ?> / <?= esc($li['unit']) ?></div>
                  <div style="font-size:0.75rem; color:var(--slate-500);">Stok: <?= number_format($li['quantity_available'], 0, ',', '.') ?> <?= esc($li['unit']) ?></div>
                </td>
                <td>
                  <?php if ($li['status'] === 'active'): ?>
                    <span class="badge badge-success">Aktif di Pasar</span>
                  <?php elseif ($li['status'] === 'moderated'): ?>
                    <span class="badge badge-danger">Ditangguhkan</span>
                  <?php elseif ($li['status'] === 'sold_out'): ?>
                    <span class="badge badge-secondary">Habis Terjual</span>
                  <?php else: ?>
                    <span class="badge badge-secondary"><?= esc(ucfirst($li['status'])) ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <form action="<?= base_url('admin/moderasi/toggle/panen/' . $li['id']) ?>" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <?php if ($li['status'] === 'active'): ?>
                      <button type="submit" class="btn btn-danger btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;" onclick="return confirm('Tangguhkan listing hasil panen ini?')">
                        Tangguhkan
                      </button>
                    <?php else: ?>
                      <button type="submit" class="btn btn-primary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">
                        Pulihkan
                      </button>
                    <?php endif; ?>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

