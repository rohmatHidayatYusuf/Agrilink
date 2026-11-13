<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Marketplace Sarana Produksi Pertanian (Saprotan)</h2>
    <p>Pengadaan benih unggul, pupuk, pestisida organik, dan alat pertanian langsung dari supplier resmi terverifikasi.</p>
  </div>
</div>

<!-- Search & Filter Bar -->
<div class="card-panel" style="margin-bottom:1.5rem; padding:1.25rem;">
  <form action="<?= base_url('saprotan') ?>" method="GET" style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center;">
    <div style="flex:2; min-width:240px;">
      <input type="text" class="form-control" name="cari" value="<?= esc($cari ?? '') ?>" placeholder="Cari benih, pupuk, alsintan...">
    </div>
    <div style="flex:1; min-width:180px;">
      <select class="form-control" name="kategori" onchange="this.form.submit()">
        <option value="all" <?= ($kategori ?? '') === 'all' ? 'selected' : '' ?>>Semua Kategori</option>
        <option value="Benih" <?= ($kategori ?? '') === 'Benih' ? 'selected' : '' ?>>Benih & Bibit</option>
        <option value="Pupuk" <?= ($kategori ?? '') === 'Pupuk' ? 'selected' : '' ?>>Pupuk Organik & Kimia</option>
        <option value="Pestisida" <?= ($kategori ?? '') === 'Pestisida' ? 'selected' : '' ?>>Pestisida & Obat Hama</option>
        <option value="Alsintan" <?= ($kategori ?? '') === 'Alsintan' ? 'selected' : '' ?>>Peralatan / Alsintan</option>
      </select>
    </div>
    <button type="submit" class="btn btn-secondary">
      <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      Filter
    </button>
  </form>
</div>

<!-- Product Grid -->
<?php if (!empty($products)): ?>
  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.25rem;">
    <?php foreach ($products as $pr): ?>
      <div class="card-panel" style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
        <div class="card-panel-body" style="padding:1.25rem;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.6rem;">
            <span class="badge badge-primary"><?= esc($pr['category']) ?></span>
            <span class="badge badge-success">Stok: <?= number_format($pr['stock'], 0, ',', '.') ?> <?= esc($pr['unit']) ?></span>
          </div>
          <h3 style="font-size:1.1rem; font-weight:800; color:var(--slate-900); margin-bottom:0.35rem; line-height:1.35;">
            <?= esc($pr['name']) ?>
          </h3>
          <p style="font-size:0.8rem; color:var(--slate-600); margin-bottom:0.75rem; line-height:1.4; min-height:40px;">
            <?= esc(substr($pr['description'] ?? 'Produk saprotan berkualitas untuk mendukung produktivitas lahan.', 0, 110)) ?>...
          </p>
          <div style="font-size:0.75rem; color:var(--slate-500); margin-bottom:1rem;">
            Supplier: <strong><?= esc($pr['supplier_name']) ?></strong> &bull; <?= esc($pr['supplier_location']) ?>
          </div>
        </div>

        <div style="padding:1rem 1.25rem; background:var(--slate-50); border-top:1px solid var(--slate-100); display:flex; justify-content:space-between; align-items:center;">
          <div>
            <div style="font-size:0.7rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Harga Satuan:</div>
            <div style="font-size:1.15rem; font-weight:800; color:var(--primary-700);">
              Rp <?= number_format($pr['price'], 0, ',', '.') ?> <span style="font-size:0.75rem; font-weight:400; color:var(--slate-500);">/ <?= esc($pr['unit']) ?></span>
            </div>
          </div>
          <button 
            type="button" 
            class="btn btn-primary btn-sm" 
            onclick="openOrderModal(<?= $pr['id'] ?>, '<?= esc(addslashes($pr['name'])) ?>', <?= (float)$pr['price'] ?>, '<?= esc($pr['unit']) ?>', <?= (float)$pr['stock'] ?>)"
          >
            Pesan &rarr;
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="card-panel">
    <div class="card-panel-body" style="padding:3rem; text-align:center; color:var(--slate-500);">
      <svg class="icon-svg" viewBox="0 0 24 24" style="width:48px; height:48px; color:var(--slate-300); margin-bottom:1rem;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
      <h3 style="color:var(--slate-800); margin-bottom:0.35rem;">Produk Tidak Ditemukan</h3>
      <p style="font-size:0.85rem;">Tidak ada produk saprotan yang cocok dengan kriteria pencarian Anda.</p>
    </div>
  </div>
<?php endif; ?>

<!-- MODAL: Pemesanan Saprotan -->
<div class="modal-box-overlay" id="orderModal" style="display:none;">
  <div class="modal-box-card" style="max-width:520px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Form Pemesanan Saprotan</h3>
      <button type="button" class="modal-close-btn" onclick="closeOrderModal()">&times;</button>
    </div>
    <form action="<?= base_url('saprotan/pesan') ?>" method="POST" id="orderForm">
      <?= csrf_field() ?>
      <input type="hidden" name="product_id" id="modalProductId">
      <div class="modal-box-body">
        
        <div style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1rem; margin-bottom:1rem;">
          <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Produk yang Dipesan:</div>
          <div id="modalProductName" style="font-size:1.05rem; font-weight:800; color:var(--slate-900); margin:0.25rem 0;"></div>
          <div style="font-size:0.85rem; color:var(--slate-600);">
            Harga: <strong id="modalProductPrice"></strong> / <span id="modalProductUnit"></span> &bull; 
            Stok Tersedia: <strong id="modalProductStock"></strong>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Jumlah Pesanan (<span id="modalInputUnit"></span>) <span class="required">*</span></label>
          <input type="number" step="1" min="1" class="form-control" name="quantity" id="modalOrderQty" value="1" oninput="calcOrderTotal()" required>
        </div>

        <div class="form-group">
          <label class="form-label">Alamat Pengiriman Lahan <span class="required">*</span></label>
          <textarea class="form-control" name="shipping_address" rows="2" placeholder="Alamat gudang / lahan penerima..." required><?= esc(session()->get('user_address')) ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Tambahan (Opsional)</label>
          <input type="text" class="form-control" name="notes" placeholder="contoh: Kirim sebelum jadwal pemupukan">
        </div>

        <!-- Ringkasan Biaya -->
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:var(--radius-md); padding:1rem; margin-top:1rem;">
          <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:var(--slate-600); margin-bottom:0.35rem;">
            <span>Subtotal Produk:</span>
            <span id="modalSubtotalText">Rp 0</span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:var(--slate-600); margin-bottom:0.5rem;">
            <span>Ongkos Pengiriman Flat:</span>
            <span>Rp 25.000</span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:1.15rem; font-weight:800; color:#15803d; border-top:1px dashed #86efac; padding-top:0.5rem;">
            <span>Total Tagihan:</span>
            <span id="modalGrandTotalText">Rp 0</span>
          </div>
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeOrderModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Konfirmasi Buat Pesanan</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let currentUnitPrice = 0;
  let currentMaxStock = 0;

  function openOrderModal(id, name, price, unit, stock) {
    document.getElementById('modalProductId').value = id;
    document.getElementById('modalProductName').textContent = name;
    document.getElementById('modalProductPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('modalProductUnit').textContent = unit;
    document.getElementById('modalInputUnit').textContent = unit;
    document.getElementById('modalProductStock').textContent = stock.toLocaleString('id-ID') + ' ' + unit;
    
    currentUnitPrice = price;
    currentMaxStock = stock;

    document.getElementById('modalOrderQty').value = 1;
    document.getElementById('modalOrderQty').max = stock;

    calcOrderTotal();
    document.getElementById('orderModal').style.display = 'flex';
  }

  function closeOrderModal() {
    document.getElementById('orderModal').style.display = 'none';
  }

  function calcOrderTotal() {
    const qty = parseFloat(document.getElementById('modalOrderQty').value) || 0;
    const subtotal = qty * currentUnitPrice;
    const total = subtotal + 25000;

    document.getElementById('modalSubtotalText').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('modalGrandTotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }
</script>
<?= $this->endSection() ?>

