<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Katalog Komoditas Pertanian Segar (Pasar Buyer)</h2>
    <p>Beli langsung hasil panen berkualitas dari petani lokal terpercaya tanpa perantara tengkulak berbelit.</p>
  </div>
</div>

<!-- Search Bar -->
<div class="card-panel" style="margin-bottom:1.5rem; padding:1.25rem;">
  <form action="<?= base_url('buyer/katalog') ?>" method="GET" style="display:flex; gap:0.75rem;">
    <input type="text" class="form-control" name="cari" value="<?= esc($cari ?? '') ?>" placeholder="Cari beras, cabai rawit, sayuran segar..." style="flex:1;">
    <button type="submit" class="btn btn-primary">
      <svg class="icon-svg" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      Cari Komoditas
    </button>
  </form>
</div>

<!-- Produce Cards Grid -->
<?php if (!empty($listings)): ?>
  <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1.25rem;">
    <?php foreach ($listings as $li): ?>
      <div class="card-panel" style="display:flex; flex-direction:column; justify-content:space-between; height:100%;">
        <div class="card-panel-body" style="padding:1.25rem;">
          <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.5rem;">
            <span class="badge badge-primary"><?= esc($li['commodity']) ?></span>
            <span class="badge badge-success">Stok: <?= number_format($li['quantity_available'], 0, ',', '.') ?> <?= esc($li['unit']) ?></span>
          </div>
          <h3 style="font-size:1.1rem; font-weight:800; color:var(--slate-900); margin-bottom:0.35rem; line-height:1.35;">
            <?= esc($li['title']) ?>
          </h3>
          <p style="font-size:0.8rem; color:var(--slate-600); margin-bottom:0.75rem; line-height:1.4; min-height:40px;">
            <?= esc(substr($li['description'] ?? '', 0, 110)) ?>...
          </p>
          <div style="font-size:0.75rem; color:var(--slate-500); margin-bottom:0.75rem;">
            Petani: <strong><?= esc($li['seller_name']) ?></strong> &bull; <?= esc($li['seller_address']) ?>
          </div>
          <div style="font-size:0.75rem; color:var(--slate-400);">
            Min. Pembelian: <strong><?= number_format($li['min_order'], 0, ',', '.') ?> <?= esc($li['unit']) ?></strong>
          </div>
        </div>

        <div style="padding:1rem 1.25rem; background:var(--slate-50); border-top:1px solid var(--slate-100); display:flex; justify-content:space-between; align-items:center;">
          <div>
            <div style="font-size:0.7rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Harga Satuan:</div>
            <div style="font-size:1.15rem; font-weight:800; color:var(--primary-700);">
              Rp <?= number_format($li['price_per_unit'], 0, ',', '.') ?> <span style="font-size:0.75rem; font-weight:400; color:var(--slate-500);">/ <?= esc($li['unit']) ?></span>
            </div>
          </div>
          <button 
            type="button" 
            class="btn btn-primary btn-sm" 
            onclick="openBuyerOrderModal(<?= $li['id'] ?>, '<?= esc(addslashes($li['title'])) ?>', <?= (float)$li['price_per_unit'] ?>, '<?= esc($li['unit']) ?>', <?= (float)$li['quantity_available'] ?>, <?= (float)$li['min_order'] ?>)"
          >
            Beli Sekarang &rarr;
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="card-panel">
    <div class="card-panel-body" style="padding:3rem; text-align:center; color:var(--slate-500);">
      <svg class="icon-svg" viewBox="0 0 24 24" style="width:48px; height:48px; color:var(--slate-300); margin-bottom:1rem;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <h3 style="color:var(--slate-800); margin-bottom:0.35rem;">Tidak Ada Komoditas Tersedia</h3>
      <p style="font-size:0.85rem;">Belum ada hasil tani yang memenuhi pencarian Anda.</p>
    </div>
  </div>
<?php endif; ?>

<!-- MODAL: Pemesanan Komoditas oleh Buyer -->
<div class="modal-box-overlay" id="buyerOrderModal" style="display:none;">
  <div class="modal-box-card" style="max-width:520px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Form Pemesanan Hasil Panen</h3>
      <button type="button" class="modal-close-btn" onclick="closeBuyerOrderModal()">&times;</button>
    </div>
    <form action="<?= base_url('buyer/pesan') ?>" method="POST" id="buyerOrderForm">
      <?= csrf_field() ?>
      <input type="hidden" name="listing_id" id="bModalListingId">
      <div class="modal-box-body">
        
        <div style="background:#f8fafc; border:1px solid var(--slate-200); border-radius:var(--radius-md); padding:1rem; margin-bottom:1rem;">
          <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Komoditas:</div>
          <div id="bModalTitle" style="font-size:1.05rem; font-weight:800; color:var(--slate-900); margin:0.25rem 0;"></div>
          <div style="font-size:0.85rem; color:var(--slate-600);">
            Harga: <strong id="bModalPrice"></strong> / <span id="bModalUnit"></span> &bull; 
            Stok: <strong id="bModalStock"></strong>
          </div>
          <div style="font-size:0.75rem; color:var(--slate-500); margin-top:0.25rem;">
            Min. Pembelian: <strong id="bModalMinOrder"></strong>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Kuantitas Pembelian (<span id="bModalInputUnit"></span>) <span class="required">*</span></label>
          <input type="number" step="1" class="form-control" name="quantity" id="bModalQty" oninput="calcBuyerTotal()" required>
        </div>

        <div class="form-group">
          <label class="form-label">Alamat Pengiriman / Gudang Pembeli <span class="required">*</span></label>
          <textarea class="form-control" name="delivery_address" rows="2" placeholder="Alamat lengkap penerima komoditas..." required><?= esc(session()->get('user_address')) ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Pengemasan / Pengiriman (Opsional)</label>
          <input type="text" class="form-control" name="notes" placeholder="contoh: Dikemas dalam keranjang kayu, kirim pagi hari">
        </div>

        <!-- Ringkasan Biaya -->
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:var(--radius-md); padding:1rem; margin-top:1rem;">
          <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:var(--slate-600); margin-bottom:0.35rem;">
            <span>Subtotal Komoditas:</span>
            <span id="bModalSubtotalText">Rp 0</span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:0.85rem; color:var(--slate-600); margin-bottom:0.5rem;">
            <span>Ongkos Pengiriman Flat:</span>
            <span>Rp 50.000</span>
          </div>
          <div style="display:flex; justify-content:space-between; font-size:1.15rem; font-weight:800; color:#15803d; border-top:1px dashed #86efac; padding-top:0.5rem;">
            <span>Total Tagihan:</span>
            <span id="bModalGrandTotalText">Rp 0</span>
          </div>
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeBuyerOrderModal()">Batal</button>
        <button type="submit" class="btn btn-primary">Konfirmasi Pesan Komoditas</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let bUnitPrice = 0;
  let bMaxStock = 0;
  let bMinOrder = 1;

  function openBuyerOrderModal(id, title, price, unit, stock, minOrder) {
    document.getElementById('bModalListingId').value = id;
    document.getElementById('bModalTitle').textContent = title;
    document.getElementById('bModalPrice').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('bModalUnit').textContent = unit;
    document.getElementById('bModalInputUnit').textContent = unit;
    document.getElementById('bModalStock').textContent = stock.toLocaleString('id-ID') + ' ' + unit;
    document.getElementById('bModalMinOrder').textContent = minOrder.toLocaleString('id-ID') + ' ' + unit;
    
    bUnitPrice = price;
    bMaxStock = stock;
    bMinOrder = minOrder;

    const inputQty = document.getElementById('bModalQty');
    inputQty.value = minOrder;
    inputQty.min = minOrder;
    inputQty.max = stock;

    calcBuyerTotal();
    document.getElementById('buyerOrderModal').style.display = 'flex';
  }

  function closeBuyerOrderModal() {
    document.getElementById('buyerOrderModal').style.display = 'none';
  }

  function calcBuyerTotal() {
    const qty = parseFloat(document.getElementById('bModalQty').value) || 0;
    const subtotal = qty * bUnitPrice;
    const total = subtotal + 50000;

    document.getElementById('bModalSubtotalText').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('bModalGrandTotalText').textContent = 'Rp ' + total.toLocaleString('id-ID');
  }
</script>
<?= $this->endSection() ?>

