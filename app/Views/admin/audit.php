<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="section-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
  <div class="section-title-group">
    <h2>Jejak Audit Aktivitas Sistem (Audit Trail)</h2>
    <p>Pencatatan menyeluruh setiap tindakan administratif penting demi transparansi dan integritas data (PRD ADM-06).</p>
  </div>
</div>

<div class="card-panel">
  <div class="card-panel-header" style="display:flex; justify-content:space-between; align-items:center;">
    <span class="card-panel-title">Log Aktivitas Administratif Terkini</span>
    <span class="badge badge-info"><?= count($logs) ?> Jejak Log</span>
  </div>
  <div class="card-panel-body" style="padding:0;">
    <?php if (!empty($logs)): ?>
      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Waktu Kejadian</th>
              <th>Administrator</th>
              <th>Aksi Administratif</th>
              <th>Entitas Terkait</th>
              <th>Rincian Catatan Tindakan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $log): ?>
              <tr>
                <td><strong>#<?= $log['id'] ?></strong></td>
                <td><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                <td style="font-weight:700; color:var(--slate-900);"><?= esc($log['admin_name']) ?></td>
                <td><span class="badge badge-primary"><?= esc($log['action']) ?></span></td>
                <td><code><?= esc($log['entity_type']) ?> #<?= $log['entity_id'] ?></code></td>
                <td style="font-size:0.85rem; color:var(--slate-700);"><?= esc($log['details']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div style="padding:3rem; text-align:center; color:var(--slate-500);">
        Belum ada aktivitas administratif yang tercatat. Setiap tindakan moderasi atau perubahan status user akan otomatis tercatat di sini.
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

