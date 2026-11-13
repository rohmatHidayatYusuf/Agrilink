<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Status & Konfigurasi Paket Langganan</h2>
    <p>Pengelolaan hak akses paket AgriLink sesuai ketentuan model bisnis PRD v1.0 (Bab 6.8 Langganan Premium).</p>
  </div>
</div>

<div class="card-panel" style="margin-bottom:1.5rem;">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Informasi Langganan Aktif Pengguna</span>
    <span class="badge badge-success">Langganan Aktif (MVP Access)</span>
  </div>
  <div class="card-panel-body">
    <div style="background:var(--slate-50); border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1.25rem; margin-bottom:1.5rem;">
      <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Paket Pengguna Terdaftar:</div>
      <div style="font-size:1.35rem; font-weight:800; color:var(--slate-900); margin:0.35rem 0;">
        <?= esc($subscription['plan_name'] ?? 'Paket Akses MVP Terintegrasi') ?>
      </div>
      <div style="font-size:0.85rem; color:var(--slate-600); line-height:1.5;">
        Sesuai ketentuan PRD Bab 6.8: Rincian pembatasan fitur berbayar dan struktur harga komersial masih dalam tahap validasi produk. Selama masa MVP, seluruh akun petani yang terdaftar diberikan hak akses penuh ke alur perencanaan lahan, estimasi, marketplace saprotan, pencatatan panen, dan pasar penjualan hasil pertanian.
      </div>
    </div>

    <div class="grid-2col" style="gap:1.25rem;">
      <div style="border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1.25rem; background:#fff;">
        <h4 style="font-size:0.95rem; margin-bottom:0.75rem; font-weight:700; color:var(--slate-900);">Fitur Hak Akses Aktif (MVP):</h4>
        <ul style="padding-left:1.25rem; font-size:0.85rem; color:var(--slate-700); line-height:1.7;">
          <li>Pengelolaan multi-lahan tanpa batasan kuota</li>
          <li>Pembuatan rencana tanam & estimasi modal otomatis</li>
          <li>Akses pemesanan ke seluruh katalog supplier saprotan terdaftar</li>
          <li>Pencatatan panen fisik & publikasi ke pasar hasil tani</li>
          <li>Pelacakan snapshot status pesanan 4-tahap (PRD 8.1)</li>
        </ul>
      </div>

      <div style="border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1.25rem; background:#fff;">
        <h4 style="font-size:0.95rem; margin-bottom:0.75rem; font-weight:700; color:var(--slate-900);">Jaminan Integritas Data Riwayat:</h4>
        <p style="font-size:0.85rem; color:var(--slate-600); line-height:1.6;">
          Sesuai PRD 6.8 & 7.3: Apabila aturan paket premium komersial diterapkan pada fase V2 nantinya, seluruh catatan historis rencana tanam, estimasi biaya, catatan panen fisik, dan transaksi pesanan Anda tetap dipertahankan secara utuh dan tidak akan pernah dihapus.
        </p>
      </div>
    </div>
  </div>
</div>

<div class="card-panel">
  <div class="card-panel-header">
    <span class="card-panel-title">Tingkatan Paket Langganan AgriLink (Roadmap PRD)</span>
  </div>
  <div class="card-panel-body">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem;">
      <?php foreach ($plans as $p): ?>
        <div style="border:2px solid <?= $p['price'] == 0 ? 'var(--primary-600)' : 'var(--slate-200)' ?>; border-radius:var(--radius-lg); padding:1.5rem; background:#fff; display:flex; flex-direction:column; justify-content:space-between;">
          <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
              <h3 style="font-size:1.15rem; font-weight:800; color:var(--slate-900); margin:0;"><?= esc($p['name']) ?></h3>
              <?php if ($p['price'] == 0): ?>
                <span class="badge badge-success">Paket Aktif</span>
              <?php else: ?>
                <span class="badge badge-primary">V2 Coming Soon</span>
              <?php endif; ?>
            </div>
            
            <div style="font-size:1.5rem; font-weight:800; color:var(--primary-700); margin-bottom:1rem;">
              <?php if ($p['price'] == 0): ?>
                GRATIS (MVP)
              <?php else: ?>
                Rp <?= number_format($p['price'], 0, ',', '.') ?> <span style="font-size:0.8rem; font-weight:400; color:var(--slate-500);">/ <?= esc($p['billing_period']) ?></span>
              <?php endif; ?>
            </div>

            <div style="font-size:0.85rem; color:var(--slate-600); line-height:1.6; border-top:1px solid var(--slate-100); padding-top:0.75rem;">
              <strong>Fitur yang disediakan:</strong><br>
              <?= esc($p['features']) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

