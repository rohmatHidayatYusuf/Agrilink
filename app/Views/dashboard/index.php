<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Profile Banner Card -->
<div class="card-panel" style="margin-bottom:1.5rem; background:linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
  <div class="card-panel-body" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
    <div style="display:flex; align-items:center; gap:1.25rem;">
      <div class="user-avatar-circle" style="width:58px; height:58px; font-size:1.35rem; background:linear-gradient(135deg, var(--primary-600), var(--primary-800)); color:#fff; display:flex; align-items:center; justify-content:center; border-radius:50%; box-shadow:0 4px 6px -1px rgba(22, 163, 74, 0.3);">
        <?= strtoupper(substr((string) session()->get('user_name'), 0, 2)) ?>
      </div>
      <div>
        <h2 style="font-size:1.35rem; font-weight:800; color:var(--slate-900); margin:0 0 0.25rem 0;">
          Halo, <?= esc(session()->get('user_name')) ?>!
        </h2>
        <div style="font-size:0.84rem; color:var(--slate-600); display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
          <span class="badge badge-success"><?= strtoupper(esc($role)) ?></span>
          <span>&bull;</span>
          <span><?= esc(session()->get('user_email')) ?></span>
          <span>&bull;</span>
          <span style="color:var(--primary-700); font-weight:600;"><?= esc($subscription['plan_name'] ?? 'Paket Akses MVP Terintegrasi') ?></span>
        </div>
      </div>
    </div>

    <div>
      <?php if ($role === 'petani'): ?>
        <a href="<?= base_url('lahan') ?>" class="btn btn-primary" style="text-decoration:none;">
          <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Mulai Rencana Tanam Baru
        </a>
      <?php elseif ($role === 'supplier'): ?>
        <a href="<?= base_url('supplier/katalog') ?>" class="btn btn-primary" style="text-decoration:none;">
          <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Tambah Produk Saprotan
        </a>
      <?php elseif ($role === 'buyer'): ?>
        <a href="<?= base_url('buyer/katalog') ?>" class="btn btn-primary" style="text-decoration:none;">
          <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
          Jelajahi Hasil Panen
        </a>
      <?php elseif ($role === 'admin'): ?>
        <a href="<?= base_url('admin/users') ?>" class="btn btn-primary" style="text-decoration:none;">
          <svg class="icon-svg" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          Kelola Pengguna
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Quick Stat Metrics Grid -->
<div class="stats-overview-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1rem; margin-bottom:1.5rem;">
  <?php if ($role === 'petani'): ?>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Lahan Dikelola</div>
      <div class="kpi-figure-value"><?= esc($stats['total_farms'] ?? 0) ?> Lahan</div>
      <div class="kpi-subtext-hint">Total Luas: <?= number_format($stats['total_area'] ?? 0, 1) ?> Ha</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Rencana Tanam Aktif</div>
      <div class="kpi-figure-value"><?= esc($stats['active_plans'] ?? 0) ?> Komoditas</div>
      <div class="kpi-subtext-hint">Dalam pemeliharaan</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Catatan Panen</div>
      <div class="kpi-figure-value"><?= esc($stats['harvest_records'] ?? 0) ?> Kali Panen</div>
      <div class="kpi-subtext-hint"><?= esc($stats['active_listings'] ?? 0) ?> listing dijual di pasar</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Pesanan Masuk</div>
      <div class="kpi-figure-value" style="color:var(--warning-700);"><?= esc($stats['pending_orders'] ?? 0) ?> Pesanan</div>
      <div class="kpi-subtext-hint">Perlu diproses segera</div>
    </div>

  <?php elseif ($role === 'supplier'): ?>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Produk Saprotan</div>
      <div class="kpi-figure-value"><?= esc($stats['total_products'] ?? 0) ?> Item</div>
      <div class="kpi-subtext-hint"><?= esc($stats['active_products'] ?? 0) ?> aktif di marketplace</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Pesanan Menunggu</div>
      <div class="kpi-figure-value" style="color:var(--warning-700);"><?= esc($stats['pending_orders'] ?? 0) ?> Pesanan</div>
      <div class="kpi-subtext-hint">Perlu konfirmasi stok</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Total Transaksi</div>
      <div class="kpi-figure-value"><?= esc($stats['total_orders'] ?? 0) ?> Pesanan</div>
      <div class="kpi-subtext-hint">Riwayat pengadaan petani</div>
    </div>

  <?php elseif ($role === 'buyer'): ?>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Komoditas Tersedia</div>
      <div class="kpi-figure-value"><?= esc($stats['available_commodities'] ?? 0) ?> Pilihan</div>
      <div class="kpi-subtext-hint">Segar langsung dari petani</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Pesanan Berjalan</div>
      <div class="kpi-figure-value" style="color:var(--primary-700);"><?= esc($stats['active_orders'] ?? 0) ?> Pesanan</div>
      <div class="kpi-subtext-hint">Sedang diproses/dikirim</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Pembelian Selesai</div>
      <div class="kpi-figure-value"><?= esc($stats['completed_orders'] ?? 0) ?> Transaksi</div>
      <div class="kpi-subtext-hint">Telah diterima</div>
    </div>

  <?php elseif ($role === 'admin'): ?>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Total Pengguna</div>
      <div class="kpi-figure-value"><?= esc($stats['total_users'] ?? 0) ?> Akun</div>
      <div class="kpi-subtext-hint"><?= esc($stats['total_farmers'] ?? 0) ?> Petani, <?= esc($stats['total_suppliers'] ?? 0) ?> Supplier, <?= esc($stats['total_buyers'] ?? 0) ?> Buyer</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Listing & Produk</div>
      <div class="kpi-figure-value"><?= esc($stats['total_products'] + $stats['total_listings']) ?> Item</div>
      <div class="kpi-subtext-hint"><?= esc($stats['total_products']) ?> Saprotan, <?= esc($stats['total_listings']) ?> Hasil Tani</div>
    </div>
    <div class="stat-kpi-card">
      <div class="kpi-meta-title">Total Pesanan</div>
      <div class="kpi-figure-value"><?= esc($stats['total_orders'] ?? 0) ?> Transaksi</div>
      <div class="kpi-subtext-hint">Dalam sistem AgriLink</div>
    </div>
  <?php endif; ?>
</div>

<!-- Detailed Data Sections Based on Role -->
<?php if ($role === 'petani'): ?>
  <div class="grid-2col" style="gap:1.5rem;">
    <!-- Rencana Tanam Terkini -->
    <div class="card-panel">
      <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span class="card-panel-title">Jadwal Rencana Tanam Aktif</span>
        <a href="<?= base_url('lahan') ?>" style="font-size:0.8rem; font-weight:700; color:var(--primary-700); text-decoration:none;">Lihat Semua &rarr;</a>
      </div>
      <div class="card-panel-body" style="padding:0;">
        <?php if (!empty($recent_plans)): ?>
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Komoditas</th>
                  <th>Lahan</th>
                  <th>Luas</th>
                  <th>Estimasi Panen</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (array_slice($recent_plans, 0, 5) as $rp): ?>
                  <tr>
                    <td style="font-weight:700; color:var(--slate-900);"><?= esc($rp['commodity']) ?></td>
                    <td><?= esc($rp['farm_name']) ?></td>
                    <td><?= number_format($rp['planned_area'], 1) ?> Ha</td>
                    <td><span class="badge badge-primary"><?= date('d M Y', strtotime($rp['expected_harvest_date'])) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div style="padding:2rem; text-align:center; color:var(--slate-500); font-size:0.85rem;">
            Belum ada rencana tanam aktif. Buat di menu <a href="<?= base_url('lahan') ?>" style="color:var(--primary-700); font-weight:700;">Perencanaan Lahan</a>.
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Hasil Panen Terkini -->
    <div class="card-panel">
      <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <span class="card-panel-title">Catatan Panen Terakhir</span>
        <a href="<?= base_url('panen') ?>" style="font-size:0.8rem; font-weight:700; color:var(--primary-700); text-decoration:none;">Lihat Semua &rarr;</a>
      </div>
      <div class="card-panel-body" style="padding:0;">
        <?php if (!empty($recent_harvests)): ?>
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Komoditas</th>
                  <th>Tanggal</th>
                  <th>Hasil Panen</th>
                  <th>Sisa Tersedia</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach (array_slice($recent_harvests, 0, 5) as $rh): ?>
                  <tr>
                    <td style="font-weight:700; color:var(--slate-900);"><?= esc($rh['commodity']) ?></td>
                    <td><?= date('d M Y', strtotime($rh['harvest_date'])) ?></td>
                    <td><?= number_format($rh['quantity'], 0, ',', '.') ?> <?= esc($rh['unit']) ?></td>
                    <td><span class="badge badge-success"><?= number_format($rh['quantity_remaining'], 0, ',', '.') ?> <?= esc($rh['unit']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div style="padding:2rem; text-align:center; color:var(--slate-500); font-size:0.85rem;">
            Belum ada catatan panen fisik. Catat di menu <a href="<?= base_url('panen') ?>" style="color:var(--primary-700); font-weight:700;">Pencatatan Panen</a>.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

<?php elseif ($role === 'supplier'): ?>
  <div class="card-panel">
    <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
      <span class="card-panel-title">Pesanan Saprotan Masuk Terbaru</span>
      <a href="<?= base_url('supplier/pesanan') ?>" style="font-size:0.8rem; font-weight:700; color:var(--primary-700); text-decoration:none;">Kelola Semua Pesanan &rarr;</a>
    </div>
    <div class="card-panel-body" style="padding:0;">
      <?php if (!empty($recent_orders)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Pemesan (Petani)</th>
                <th>Total Tagihan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_slice($recent_orders, 0, 5) as $ro): ?>
                <tr>
                  <td style="font-weight:700;"><?= esc($ro['order_number']) ?></td>
                  <td><?= esc($ro['buyer_name']) ?></td>
                  <td>Rp <?= number_format($ro['total_amount'], 0, ',', '.') ?></td>
                  <td><span class="badge badge-warning"><?= strtoupper(str_replace('_', ' ', $ro['status'])) ?></span></td>
                  <td><a href="<?= base_url('supplier/pesanan') ?>" class="btn btn-secondary btn-sm" style="padding:0.25rem 0.5rem; font-size:0.75rem;">Proses</a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="padding:2rem; text-align:center; color:var(--slate-500);">Belum ada pesanan masuk dari petani.</div>
      <?php endif; ?>
    </div>
  </div>

<?php elseif ($role === 'buyer'): ?>
  <div class="card-panel">
    <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
      <span class="card-panel-title">Pesanan Komoditas Segar Terkini</span>
      <a href="<?= base_url('buyer/pesanan') ?>" style="font-size:0.8rem; font-weight:700; color:var(--primary-700); text-decoration:none;">Lihat Semua &rarr;</a>
    </div>
    <div class="card-panel-body" style="padding:0;">
      <?php if (!empty($recent_orders)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Petani (Penjual)</th>
                <th>Total Biaya</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_slice($recent_orders, 0, 5) as $bo): ?>
                <tr>
                  <td style="font-weight:700;"><?= esc($bo['order_number']) ?></td>
                  <td><?= esc($bo['seller_name']) ?></td>
                  <td>Rp <?= number_format($bo['total_amount'], 0, ',', '.') ?></td>
                  <td><span class="badge badge-primary"><?= strtoupper(str_replace('_', ' ', $bo['status'])) ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="padding:2rem; text-align:center; color:var(--slate-500);">
          Belum ada riwayat pesanan. Mulai belanja komoditas di <a href="<?= base_url('buyer/katalog') ?>" style="color:var(--primary-700); font-weight:700;">Katalog Hasil Tani</a>.
        </div>
      <?php endif; ?>
    </div>
  </div>

<?php elseif ($role === 'admin'): ?>
  <div class="card-panel">
    <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
      <span class="card-panel-title">Jejak Audit Aktivitas Sistem Terkini</span>
      <a href="<?= base_url('admin/audit') ?>" style="font-size:0.8rem; font-weight:700; color:var(--primary-700); text-decoration:none;">Audit Penuh &rarr;</a>
    </div>
    <div class="card-panel-body" style="padding:0;">
      <?php if (!empty($recent_audits)): ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>Waktu</th>
                <th>Administrator</th>
                <th>Aksi</th>
                <th>Detail</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recent_audits as $log): ?>
                <tr>
                  <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                  <td style="font-weight:700;"><?= esc($log['admin_name']) ?></td>
                  <td><span class="badge badge-info"><?= esc($log['action']) ?></span></td>
                  <td style="font-size:0.8rem; color:var(--slate-600);"><?= esc($log['details']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="padding:2rem; text-align:center; color:var(--slate-500);">Belum ada catatan log aktivitas administratif.</div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>

