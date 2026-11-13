<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Kalkulator Estimasi Kebutuhan & Modal Usaha Tani</h2>
    <p>Hitung kebutuhan benih, pupuk, sarana produksi, dan perkiraan biaya modal sebelum musim tanam.</p>
  </div>
  <button type="button" class="btn btn-primary" onclick="openModal('addEstimateModal')">
    <svg class="icon-svg" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    + Hitung Estimasi Baru
  </button>
</div>

<!-- Daftar Estimasi Tersimpan -->
<?php if (!empty($estimates)): ?>
  <div style="display:flex; flex-direction:column; gap:1.25rem;">
    <?php foreach ($estimates as $est): ?>
      <div class="card-panel">
        <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
          <div>
            <span class="card-panel-title" style="font-size:1.05rem;">
              Estimasi: <?= esc($est['commodity']) ?> (<?= esc($est['farm_name']) ?> - <?= number_format($est['planned_area'], 1) ?> Ha)
            </span>
            <div style="font-size:0.75rem; color:var(--slate-500); margin-top:0.2rem;">
              Dibuat tanggal: <?= date('d M Y H:i', strtotime($est['created_at'])) ?>
            </div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--slate-400); font-weight:700;">Total Estimasi Modal</div>
            <div style="font-size:1.25rem; font-weight:800; color:var(--primary-700);">
              Rp <?= number_format($est['total_estimated_cost'], 0, ',', '.') ?>
            </div>
          </div>
        </div>

        <div class="card-panel-body" style="padding:0;">
          <div class="table-responsive">
            <table class="data-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Kategori</th>
                  <th>Item Kebutuhan</th>
                  <th>Kuantitas</th>
                  <th>Harga Satuan (Rp)</th>
                  <th>Subtotal (Rp)</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($est['items'])): ?>
                  <?php $no = 1; foreach ($est['items'] as $item): ?>
                    <tr>
                      <td style="width:40px;"><?= $no++ ?></td>
                      <td><span class="badge badge-secondary"><?= esc($item['category']) ?></span></td>
                      <td style="font-weight:600; color:var(--slate-800);"><?= esc($item['item_name']) ?></td>
                      <td><?= number_format($item['quantity'], 1) ?> <?= esc($item['unit']) ?></td>
                      <td>Rp <?= number_format($item['estimated_unit_price'], 0, ',', '.') ?></td>
                      <td style="font-weight:700; color:var(--slate-900);">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
          <?php if (!empty($est['notes'])): ?>
            <div style="padding:0.75rem 1.25rem; background:#f8fafc; font-size:0.8rem; color:var(--slate-600); border-top:1px solid var(--slate-100);">
              <strong>Catatan:</strong> <?= esc($est['notes']) ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <div class="card-panel">
    <div class="card-panel-body" style="padding:3rem; text-align:center; color:var(--slate-500);">
      <svg class="icon-svg" viewBox="0 0 24 24" style="width:48px; height:48px; color:var(--slate-300); margin-bottom:1rem;"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
      <h3 style="color:var(--slate-800); margin-bottom:0.35rem;">Belum Ada Estimasi Modal</h3>
      <p style="font-size:0.85rem; max-width:460px; margin:0 auto 1.5rem auto;">Hitung perkiraan kebutuhan sarana produksi dan anggaran modal untuk rencana tanam Anda.</p>
      <button type="button" class="btn btn-primary" onclick="openModal('addEstimateModal')">
        + Buat Estimasi Pertama
      </button>
    </div>
  </div>
<?php endif; ?>

<!-- MODAL: Hitung Estimasi Baru -->
<div class="modal-box-overlay" id="addEstimateModal" style="display:none;">
  <div class="modal-box-card" style="max-width:780px;">
    <div class="modal-box-header">
      <h3 class="modal-box-title">Kalkulator Estimasi Kebutuhan & Modal</h3>
      <button type="button" class="modal-close-btn" onclick="closeModal('addEstimateModal')">&times;</button>
    </div>
    <form action="<?= base_url('estimasi/simpan') ?>" method="POST" id="estimateForm">
      <?= csrf_field() ?>
      <div class="modal-box-body">
        
        <div class="form-group">
          <label class="form-label">Pilih Rencana Tanam Terkait <span class="required">*</span></label>
          <select class="form-control" name="farm_plan_id" required>
            <option value="">-- Pilih Rencana Penanaman --</option>
            <?php foreach ($plans as $p): ?>
              <option value="<?= $p['id'] ?>">
                <?= esc($p['commodity']) ?> — <?= esc($p['farm_name']) ?> (<?= $p['planned_area'] ?> Ha)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; margin:1.25rem 0 0.5rem 0;">
          <h4 style="font-size:0.95rem; font-weight:700; color:var(--slate-800); margin:0;">Daftar Item Kebutuhan & Biaya:</h4>
          <button type="button" class="btn btn-secondary btn-sm" onclick="addItemRow()">+ Tambah Baris Item</button>
        </div>

        <div class="table-responsive" style="margin-bottom:1rem;">
          <table class="data-table" id="itemsTable">
            <thead>
              <tr>
                <th style="min-width:140px;">Kategori</th>
                <th style="min-width:180px;">Nama Kebutuhan</th>
                <th style="width:90px;">Jumlah</th>
                <th style="width:90px;">Satuan</th>
                <th style="width:130px;">Harga Satuan</th>
                <th style="width:130px;">Subtotal</th>
                <th style="width:40px;"></th>
              </tr>
            </thead>
            <tbody id="itemsBody">
              <!-- Baris Template Awal -->
              <tr>
                <td>
                  <select class="form-control form-control-sm" name="item_category[]">
                    <option value="Benih">Benih</option>
                    <option value="Pupuk">Pupuk</option>
                    <option value="Pestisida">Pestisida</option>
                    <option value="Alsintan">Alsintan</option>
                    <option value="Tenaga Kerja">Tenaga Kerja</option>
                    <option value="Lainnya">Lainnya</option>
                  </select>
                </td>
                <td>
                  <input type="text" class="form-control form-control-sm" name="item_name[]" placeholder="Nama barang / sarana" required>
                </td>
                <td>
                  <input type="number" step="0.1" min="0.1" class="form-control form-control-sm row-qty" name="item_quantity[]" value="1" oninput="calcRow(this)" required>
                </td>
                <td>
                  <input type="text" class="form-control form-control-sm" name="item_unit[]" value="kg" required>
                </td>
                <td>
                  <input type="number" step="100" min="0" class="form-control form-control-sm row-price" name="item_price[]" value="50000" oninput="calcRow(this)" required>
                </td>
                <td>
                  <span class="row-subtotal" style="font-weight:700; font-size:0.85rem;">Rp 50.000</span>
                </td>
                <td>
                  <button type="button" style="background:none; border:none; color:var(--danger-600); cursor:pointer; font-size:1.1rem;" onclick="removeRow(this)">&times;</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:var(--radius-md); padding:1rem; display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <span style="font-weight:700; color:#166534; font-size:0.95rem;">Total Estimasi Modal (Σ Subtotal):</span>
          <span id="grandTotalText" style="font-size:1.35rem; font-weight:800; color:#15803d;">Rp 50.000</span>
        </div>

        <div class="form-group">
          <label class="form-label">Catatan Tambahan (Opsional)</label>
          <textarea class="form-control" name="notes" rows="2" placeholder="Catatan analisis biaya atau asumsi harga pasar..."></textarea>
        </div>

      </div>
      <div class="modal-box-footer">
        <button type="button" class="btn btn-secondary" onclick="closeModal('addEstimateModal')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Estimasi Modal</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function openModal(id) { document.getElementById(id).style.display = 'flex'; }
  function closeModal(id) { document.getElementById(id).style.display = 'none'; }

  function calcRow(el) {
    const row = el.closest('tr');
    const qty = parseFloat(row.querySelector('.row-qty').value) || 0;
    const price = parseFloat(row.querySelector('.row-price').value) || 0;
    const subtotal = qty * price;
    row.querySelector('.row-subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    calcGrandTotal();
  }

  function calcGrandTotal() {
    let grand = 0;
    document.querySelectorAll('#itemsBody tr').forEach(row => {
      const qty = parseFloat(row.querySelector('.row-qty').value) || 0;
      const price = parseFloat(row.querySelector('.row-price').value) || 0;
      grand += (qty * price);
    });
    document.getElementById('grandTotalText').textContent = 'Rp ' + grand.toLocaleString('id-ID');
  }

  function addItemRow() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <select class="form-control form-control-sm" name="item_category[]">
          <option value="Benih">Benih</option>
          <option value="Pupuk">Pupuk</option>
          <option value="Pestisida">Pestisida</option>
          <option value="Alsintan">Alsintan</option>
          <option value="Tenaga Kerja">Tenaga Kerja</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </td>
      <td>
        <input type="text" class="form-control form-control-sm" name="item_name[]" placeholder="Nama barang / sarana" required>
      </td>
      <td>
        <input type="number" step="0.1" min="0.1" class="form-control form-control-sm row-qty" name="item_quantity[]" value="1" oninput="calcRow(this)" required>
      </td>
      <td>
        <input type="text" class="form-control form-control-sm" name="item_unit[]" value="kg" required>
      </td>
      <td>
        <input type="number" step="100" min="0" class="form-control form-control-sm row-price" name="item_price[]" value="0" oninput="calcRow(this)" required>
      </td>
      <td>
        <span class="row-subtotal" style="font-weight:700; font-size:0.85rem;">Rp 0</span>
      </td>
      <td>
        <button type="button" style="background:none; border:none; color:var(--danger-600); cursor:pointer; font-size:1.1rem;" onclick="removeRow(this)">&times;</button>
      </td>
    `;
    tbody.appendChild(tr);
  }

  function removeRow(btn) {
    const tbody = document.getElementById('itemsBody');
    if (tbody.children.length > 1) {
      btn.closest('tr').remove();
      calcGrandTotal();
    } else {
      alert('Minimal harus ada 1 baris item estimasi.');
    }
  }
</script>
<?= $this->endSection() ?>

