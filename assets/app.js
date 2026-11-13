/**
 * AgriLink — Multi-Page Application Core Logic
 * Aligned with AgriLink PRD v1.0
 * Supports persistent state via localStorage across all separate pages
 */

// Default Seed Data Store
const defaultState = {
  activeRole: 'farmer',
  personas: {
    farmer: { name: 'Pak Budi Santoso', role: 'Petani (Produsen & Penjual)', sub: 'Lahan: Sukabumi & Lembang', avatar: 'BS' },
    supplier: { name: 'CV Tani Makmur Sejahtera', role: 'Supplier Saprotan Resmi', sub: 'Katalog: 12 Produk Aktif', avatar: 'TM' },
    buyer: { name: 'Resto Segar Nusantara', role: 'Pembeli Komersial (Buyer)', sub: 'Kebutuhan: Sayuran & Beras', avatar: 'RS' },
    admin: { name: 'Admin Pengawas Sistem', role: 'Administrator AgriLink', sub: 'Hak Akses Penuh Sistem', avatar: 'AD' }
  },

  // 1. Farms (PRD 7.1 B)
  farms: [
    { id: 1, name: 'Lahan Sukabumi Subur', location: 'Kec. Cisaat, Kab. Sukabumi', areaSize: 1.5, areaUnit: 'Ha', status: 'Aktif', soilType: 'Aluvial Beririgasi Teknis' },
    { id: 2, name: 'Kebun Lereng Lembang', location: 'Kec. Cikole, Kab. Bandung Barat', areaSize: 0.8, areaUnit: 'Ha', status: 'Aktif', soilType: 'Andosol Vulkanik Segar' }
  ],

  // 2. Farm Plans (PRD 7.1 B)
  farmPlans: [
    { id: 1, farmId: 1, farmName: 'Lahan Sukabumi Subur', commodity: 'Padi Ciherang', plannedArea: 1.0, areaUnit: 'Ha', startDate: '2026-10-10', expectedHarvestDate: '2027-01-25', status: 'Fase Vegetatif', totalEstimatedCost: 8750000, actualCost: 3500000 },
    { id: 2, farmId: 1, farmName: 'Lahan Sukabumi Subur', commodity: 'Jagung Hibrida', plannedArea: 0.5, areaUnit: 'Ha', startDate: '2026-10-15', expectedHarvestDate: '2026-12-30', status: 'Persiapan Lahan', totalEstimatedCost: 3600000, actualCost: 900000 },
    { id: 3, farmId: 2, farmName: 'Kebun Lereng Lembang', commodity: 'Cabai Merah Keriting', plannedArea: 0.8, areaUnit: 'Ha', startDate: '2026-08-15', expectedHarvestDate: '2026-11-20', status: 'Masa Panen Sebagian', totalEstimatedCost: 14200000, actualCost: 13800000 }
  ],

  // 3. Estimates & Items (PRD 7.1 B)
  estimates: [
    {
      id: 101,
      farmPlanId: 1,
      items: [
        { id: 1, category: 'Benih', name: 'Benih Padi Ciherang Bersertifikat', quantity: 25, unit: 'Kg', unitPrice: 45000, subtotal: 1125000, actualExpense: 1125000, status: 'Realisasi' },
        { id: 2, category: 'Pupuk', name: 'Pupuk Organik Kompos Granul', quantity: 1200, unit: 'Kg', unitPrice: 2000, subtotal: 2400000, actualExpense: 2375000, status: 'Realisasi' },
        { id: 3, category: 'Pupuk', name: 'Pupuk NPK 16-16-16 Formula Seimbang', quantity: 200, unit: 'Kg', unitPrice: 12500, subtotal: 2500000, actualExpense: 0, status: 'Rencana' },
        { id: 4, category: 'Proteksi', name: 'Bio-Pestisida Hayati Protek-Bio', quantity: 6, unit: 'Liter', unitPrice: 125000, subtotal: 750000, actualExpense: 0, status: 'Rencana' },
        { id: 5, category: 'Tenaga Kerja', name: 'Olah Lahan & Traktor (2 Orang / 3 Hari)', quantity: 6, unit: 'HOK', unitPrice: 150000, subtotal: 900000, actualExpense: 900000, status: 'Realisasi' },
        { id: 6, category: 'Tenaga Kerja', name: 'Tanam & Pemeliharaan Awal', quantity: 7, unit: 'HOK', unitPrice: 150000, subtotal: 1075000, actualExpense: 0, status: 'Rencana' }
      ]
    }
  ],

  // 4. Products - Saprotan (PRD 7.1 C)
  products: [
    { id: 1, supplierId: 10, supplierName: 'CV Tani Makmur Sejahtera', name: 'Benih Padi Ciherang Unggul 5Kg', category: 'benih', description: 'Daya berkecambah 95%, tahan wereng batang coklat dan hawar daun.', price: 185000, unit: 'sak (5kg)', stock: 140, status: 'Tersedia' },
    { id: 2, supplierId: 11, supplierName: 'PT Agro Nusantara Raya', name: 'Pupuk NPK 16-16-16 Mutiara', category: 'pupuk', description: 'Kandungan hara makro seimbang untuk pertumbuhan vegetatif dan generatif.', price: 620000, unit: 'sak (50kg)', stock: 85, status: 'Tersedia' },
    { id: 3, supplierId: 10, supplierName: 'CV Tani Makmur Sejahtera', name: 'Pupuk Organik Hayati Granul Super', category: 'pupuk', description: 'Memperbaiki kesuburan biologis tanah dan meningkatkan kapasitas serap hara.', price: 95000, unit: 'sak (25kg)', stock: 220, status: 'Tersedia' },
    { id: 4, supplierId: 12, supplierName: 'Agro Protect Sejahtera', name: 'Pestisida Hayati Bio-Shield 1L', category: 'proteksi', description: 'Pengendali hama ulat grayak dan jamur tanpa meninggalkan residu berbahaya.', price: 130000, unit: 'botol (1L)', stock: 40, status: 'Tersedia' },
    { id: 5, supplierId: 13, supplierName: 'Sentra Alsintan Modern', name: 'Knapsack Sprayer Elektrik 16L', category: 'alat', description: 'Pompa diafragma otomatis, baterai litium tahan 6-8 jam semprot berkelanjutan.', price: 485000, unit: 'unit', stock: 18, status: 'Tersedia' }
  ],

  // 5. Harvest Records (PRD 7.1 D)
  harvestRecords: [
    { id: 1, farmPlanId: 3, commodity: 'Cabai Merah Keriting', planName: 'Cabai Merah Keriting (Kebun Lembang)', harvestDate: '2026-10-22', quantityTotal: 850, quantityAllocated: 600, quantityAvailable: 250, unit: 'Kg', grade: 'Grade Super A', qualityNotes: 'Tingkat kemerahan 90%, tangkai segar, kadar air rendah.', isListed: true },
    { id: 2, farmPlanId: 1, commodity: 'Gabah Kering Giling (GKG)', planName: 'Padi Ciherang (Musim Lalu - Sukabumi)', harvestDate: '2026-06-18', quantityTotal: 5200, quantityAllocated: 5200, quantityAvailable: 0, unit: 'Kg', grade: 'Grade A', qualityNotes: 'Kadar air 13.5%, butir patah di bawah 8%.', isListed: false }
  ],

  // 6. Harvest Listings (PRD 7.1 D)
  harvestListings: [
    { id: 201, harvestRecordId: 1, farmerName: 'Pak Budi Santoso', title: 'Cabai Merah Keriting Segar Lembang', commodity: 'Cabai Merah Keriting', location: 'Lembang, Kab. Bandung Barat', pricePerUnit: 38000, quantityAvailable: 250, minOrder: 25, unit: 'Kg', grade: 'Grade Super A', description: 'Panen langsung pagi hari dari kebun lereng. Bersih, seragam, cocok untuk horeka dan supermarket.', status: 'Aktif' },
    { id: 202, harvestRecordId: null, farmerName: 'Ibu Sari Rahayu', title: 'Beras Pandan Wangi Organik Sukabumi', commodity: 'Beras Organik', location: 'Cisaat, Kab. Sukabumi', pricePerUnit: 18500, quantityAvailable: 1200, minOrder: 50, unit: 'Kg', grade: 'Organik Bersertifikat', description: 'Aroma wangi khas alami pandan wangi, tekstur pulen tanpa pengawet sintesis.', status: 'Aktif' },
    { id: 203, harvestRecordId: null, farmerName: 'Kelompok Tani Harapan Jaya', title: 'Jagung Manis Madu Grade A', commodity: 'Jagung Manis', location: 'Pelabuhan Ratu, Sukabumi', pricePerUnit: 7500, quantityAvailable: 800, minOrder: 100, unit: 'Kg', grade: 'Grade A', description: 'Kemanisan brix 15-16, bulir padat berisi, dipetik langsung saat kematangan optimal.', status: 'Aktif' }
  ],

  // 7. Orders & Workflow (PRD 7.1 E & 8.1)
  orders: [
    { id: 'ORD-SP-2026-081', type: 'supply', productTitle: 'Benih Padi Ciherang & Pupuk Organik', partnerName: 'CV Tani Makmur Sejahtera (Supplier)', roleInitiator: 'farmer', quantitySummary: '5 Sak Benih, 2 Sak Organik', subtotal: 1115000, shippingCost: 50000, totalAmount: 1165000, orderDate: '2026-10-24', statusCode: 3, statusLabel: 'Diproses / disiapkan' },
    { id: 'ORD-SL-2026-104', type: 'sales', productTitle: 'Cabai Merah Keriting Segar Lembang', partnerName: 'Resto Segar Nusantara (Buyer)', roleInitiator: 'buyer', quantitySummary: '150 Kg', subtotal: 5700000, shippingCost: 80000, totalAmount: 5780000, orderDate: '2026-10-25', statusCode: 1, statusLabel: 'Menunggu konfirmasi' },
    { id: 'ORD-SL-2026-092', type: 'sales', productTitle: 'Beras Pandan Wangi Sukabumi', partnerName: 'Koperasi Konsumen Sejahtera', roleInitiator: 'buyer', quantitySummary: '500 Kg', subtotal: 9250000, shippingCost: 150000, totalAmount: 9400000, orderDate: '2026-10-18', statusCode: 4, statusLabel: 'Selesai' }
  ],

  // 8. Admin Audit Logs (PRD 7.1 E)
  adminAuditLogs: [
    { id: 1, date: '2026-10-25 14:20', admin: 'Admin Pengawas', action: 'Verifikasi Akun Petani Baru', entity: 'User #104 (Pak Budi Santoso)' },
    { id: 2, date: '2026-10-24 10:15', admin: 'Admin Pengawas', action: 'Pembaruan Kebijakan Moderasi', entity: 'Katalog Saprotan Bersertifikat' },
    { id: 3, date: '2026-10-22 16:45', admin: 'Admin Pengawas', action: 'Pemeriksaan Snapshot Pesanan', entity: 'Order #ORD-SL-2026-092' }
  ]
};

// State Store with localStorage persistence
const AppState = {
  data: null,

  init() {
    try {
      const stored = localStorage.getItem('agrilink_data_store');
      if (stored) {
        this.data = JSON.parse(stored);
      } else {
        this.data = JSON.parse(JSON.stringify(defaultState));
        this.save();
      }
    } catch (e) {
      this.data = JSON.parse(JSON.stringify(defaultState));
    }
  },

  save() {
    try {
      localStorage.setItem('agrilink_data_store', JSON.stringify(this.data));
    } catch (e) {
      console.warn('localStorage not available', e);
    }
  }
};

// Utilities
function formatRupiah(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(amount);
}

function showNotification(message, type = 'info') {
  const shelf = document.getElementById('toastShelf');
  if (!shelf) return;

  const toast = document.createElement('div');
  toast.className = `toast-item ${type}`;
  toast.innerHTML = `
    <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24">
      ${type === 'success' ? '<path d="M20 6L9 17l-5-5"/>' : '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>'}
    </svg>
    <span>${message}</span>
  `;

  shelf.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(20px)';
    toast.style.transition = 'all 0.25s ease';
    setTimeout(() => toast.remove(), 250);
  }, 3500);
}

// Mobile Sidebar
function initMobileSidebar() {
  const toggleBtn = document.getElementById('mobileMenuToggle');
  const sidebar = document.getElementById('appSidebar');
  const backdrop = document.getElementById('sidebarBackdrop');

  if (toggleBtn && sidebar && backdrop) {
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('open');
      backdrop.classList.toggle('active');
    });

    backdrop.addEventListener('click', () => {
      sidebar.classList.remove('open');
      backdrop.classList.remove('active');
    });
  }
}

// -------------------------------------------------------------
// ESTIMATE CALCULATOR (estimasi-modal.html)
// -------------------------------------------------------------
function renderEstimateTable() {
  const tableBody = document.getElementById('estimateTableBody');
  const totalCostElem = document.getElementById('estimateTotalCost');
  const actualCostElem = document.getElementById('actualTotalExpense');
  if (!tableBody) return;

  const est = AppState.data.estimates[0];
  tableBody.innerHTML = '';

  let grandEstimated = 0;
  let grandActual = 0;

  est.items.forEach((item, index) => {
    const subtotal = item.quantity * item.unitPrice;
    grandEstimated += subtotal;
    grandActual += item.actualExpense || 0;

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>
        <span class="badge ${item.category === 'Benih' ? 'badge-success' : item.category === 'Pupuk' ? 'badge-warning' : 'badge-info'}">
          ${item.category}
        </span>
      </td>
      <td>
        <div class="cell-title">${item.name}</div>
        <div style="font-size: 0.72rem; color: var(--slate-400);">Status: ${item.status}</div>
      </td>
      <td>
        <input type="number" min="1" class="form-control input-compact est-qty-input" data-index="${index}" value="${item.quantity}">
        <span style="font-size: 0.75rem; color: var(--slate-500); margin-left: 0.25rem;">${item.unit}</span>
      </td>
      <td>
        <input type="number" min="0" step="500" class="form-control input-compact est-price-input" data-index="${index}" value="${item.unitPrice}">
      </td>
      <td style="font-weight: 700; color: var(--slate-900);">
        <span class="tag-estimate">ESTIMASI</span> ${formatRupiah(subtotal)}
      </td>
      <td style="font-weight: 600; color: var(--primary-800);">
        <span class="tag-actual">AKTUAL</span> ${formatRupiah(item.actualExpense || 0)}
      </td>
      <td style="text-align: right;">
        <button class="btn btn-secondary btn-sm est-delete-btn" data-index="${index}" title="Hapus Baris Kebutuhan">
          <svg class="icon-svg icon-svg-sm" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </button>
      </td>
    `;
    tableBody.appendChild(tr);
  });

  if (totalCostElem) totalCostElem.textContent = formatRupiah(grandEstimated);
  if (actualCostElem) actualCostElem.textContent = formatRupiah(grandActual);
}

function initEstimateInteractions() {
  const tableBody = document.getElementById('estimateTableBody');
  if (!tableBody) return;

  tableBody.addEventListener('input', (e) => {
    const target = e.target;
    const index = parseInt(target.dataset.index, 10);
    if (isNaN(index)) return;

    const items = AppState.data.estimates[0].items;
    if (target.classList.contains('est-qty-input')) {
      items[index].quantity = Math.max(0, parseFloat(target.value) || 0);
    } else if (target.classList.contains('est-price-input')) {
      items[index].unitPrice = Math.max(0, parseFloat(target.value) || 0);
    }
    AppState.save();
    renderEstimateTable();
  });

  tableBody.addEventListener('click', (e) => {
    const delBtn = e.target.closest('.est-delete-btn');
    if (delBtn) {
      const idx = parseInt(delBtn.dataset.index, 10);
      AppState.data.estimates[0].items.splice(idx, 1);
      AppState.save();
      renderEstimateTable();
      showNotification('Baris estimasi kebutuhan dihapus', 'warning');
    }
  });

  const btnAddItem = document.getElementById('btnAddEstimateRow');
  if (btnAddItem) {
    btnAddItem.addEventListener('click', () => {
      AppState.data.estimates[0].items.push({
        id: Date.now(),
        category: 'Kebutuhan',
        name: 'Item Sarana Produksi Baru',
        quantity: 1,
        unit: 'Unit',
        unitPrice: 50000,
        subtotal: 50000,
        actualExpense: 0,
        status: 'Rencana'
      });
      AppState.save();
      renderEstimateTable();
      showNotification('Baris kebutuhan baru ditambahkan ke kalkulator', 'success');
    });
  }

  const btnSaveEst = document.getElementById('btnSaveEstimateSheet');
  if (btnSaveEst) {
    btnSaveEst.addEventListener('click', () => {
      AppState.save();
      showNotification('Seluruh data estimasi kebutuhan dan modal berhasil disimpan!', 'success');
    });
  }
}

// -------------------------------------------------------------
// SUPPLY MARKETPLACE (marketplace-saprotan.html)
// -------------------------------------------------------------
function renderProductsMarketplace(category = 'all', keyword = '') {
  const container = document.getElementById('supplyProductsGrid');
  if (!container) return;

  const filtered = AppState.data.products.filter(item => {
    const matchCat = category === 'all' || item.category === category;
    const matchText = item.name.toLowerCase().includes(keyword.toLowerCase()) ||
                      item.supplierName.toLowerCase().includes(keyword.toLowerCase());
    return matchCat && matchText;
  });

  container.innerHTML = '';
  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="grid-column: 1/-1;">
        <svg class="empty-state-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        <div class="empty-state-title">Tidak ada sarana produksi yang sesuai</div>
        <div class="empty-state-desc">Coba sesuaikan kata kunci pencarian atau ubah filter kategori produk.</div>
      </div>
    `;
    return;
  }

  filtered.forEach(p => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <div class="product-thumb">
        <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
        <div class="product-badge-overlay">
          <span class="badge badge-info">${p.category}</span>
        </div>
      </div>
      <div class="product-details">
        <div class="product-category">${p.category}</div>
        <h4 class="product-name">${p.name}</h4>
        <p class="product-snippet">${p.description}</p>
        <div class="product-partner-row">
          <span>Supplier: <strong>${p.supplierName}</strong></span>
          <span class="badge badge-neutral">Stok: ${p.stock}</span>
        </div>
        <div class="product-bottom-row">
          <div>
            <div class="price-figure">${formatRupiah(p.price)}</div>
            <div class="price-unit">per ${p.unit}</div>
          </div>
          <button class="btn btn-primary btn-sm btn-order-supply" data-id="${p.id}">
            Pesan Produk
          </button>
        </div>
      </div>
    `;
    container.appendChild(card);
  });
}

// -------------------------------------------------------------
// HARVEST MARKETPLACE (pasar-hasil-tani.html)
// -------------------------------------------------------------
function renderHarvestMarketplace(keyword = '') {
  const container = document.getElementById('harvestMarketGrid');
  if (!container) return;

  const filtered = AppState.data.harvestListings.filter(item => {
    return item.title.toLowerCase().includes(keyword.toLowerCase()) ||
           item.commodity.toLowerCase().includes(keyword.toLowerCase()) ||
           item.farmerName.toLowerCase().includes(keyword.toLowerCase());
  });

  container.innerHTML = '';
  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="grid-column: 1/-1;">
        <svg class="empty-state-icon" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
        <div class="empty-state-title">Hasil panen belum tersedia</div>
        <div class="empty-state-desc">Belum ada penawaran panen aktif yang cocok dengan kriteria pencarian.</div>
      </div>
    `;
    return;
  }

  filtered.forEach(h => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.innerHTML = `
      <div class="product-thumb" style="background: #fdfbf7;">
        <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 1 10 10c0 5.523-4.477 10-10 10S2 17.523 2 12A10 10 0 0 1 12 2z"/><path d="M12 18V8"/><path d="M12 12c-2.5-2.5-4-1-5 1"/><path d="M12 9c2.5-2.5 4-1 5 1"/></svg>
        <div class="product-badge-overlay">
          <span class="badge badge-success">${h.grade}</span>
        </div>
      </div>
      <div class="product-details">
        <div class="product-category" style="color: var(--primary-800);">${h.commodity}</div>
        <h4 class="product-name">${h.title}</h4>
        <p class="product-snippet">${h.description}</p>
        <div class="product-partner-row">
          <span>Petani: <strong>${h.farmerName}</strong></span>
          <span>${h.location}</span>
        </div>
        <div class="product-partner-row">
          <span>Tersedia: <strong>${h.quantityAvailable} ${h.unit}</strong></span>
          <span style="font-size: 0.72rem; color: var(--slate-500);">Min. ${h.minOrder} ${h.unit}</span>
        </div>
        <div class="product-bottom-row">
          <div>
            <div class="price-figure">${formatRupiah(h.pricePerUnit)}</div>
            <div class="price-unit">per ${h.unit}</div>
          </div>
          <button class="btn btn-outline-primary btn-sm btn-order-harvest" data-id="${h.id}">
            Beli Hasil Tani
          </button>
        </div>
      </div>
    `;
    container.appendChild(card);
  });
}

// -------------------------------------------------------------
// ORDERS & LIFECYCLE TRACKER (pesanan-transaksi.html, supplier-pesanan.html, buyer-pesanan.html)
// -------------------------------------------------------------
function renderOrdersView(filterType = 'all') {
  const container = document.getElementById('ordersListContainer');
  if (!container) return;

  container.innerHTML = '';
  const filteredOrders = AppState.data.orders.filter(order => {
    if (filterType === 'supply') return order.type === 'supply';
    if (filterType === 'sales') return order.type === 'sales';
    return true;
  });

  if (filteredOrders.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg class="empty-state-icon" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        <div class="empty-state-title">Belum ada pesanan aktif</div>
        <div class="empty-state-desc">Transaksi yang dibuat akan tercatat dalam daftar ini dengan alur verifikasi resmi.</div>
      </div>
    `;
    return;
  }

  filteredOrders.forEach(order => {
    const isSupply = order.type === 'supply';
    const typeBadge = isSupply
      ? '<span class="badge badge-info">Pengadaan Saprotan</span>'
      : '<span class="badge badge-success">Penjualan Hasil Panen</span>';

    const s1 = order.statusCode >= 1 ? 'completed' : '';
    const s2 = order.statusCode >= 2 ? (order.statusCode === 2 ? 'active' : 'completed') : '';
    const s3 = order.statusCode >= 3 ? (order.statusCode === 3 ? 'active' : 'completed') : '';
    const s4 = order.statusCode === 4 ? 'completed' : '';

    const card = document.createElement('div');
    card.className = 'order-card';
    card.innerHTML = `
      <div class="order-top-meta">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
          <span class="order-id-code">${order.id}</span>
          ${typeBadge}
          <span style="font-size: 0.78rem; color: var(--slate-500);">Tanggal: ${order.orderDate}</span>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <span style="font-family: var(--font-heading); font-weight: 800; font-size: 1.05rem; color: var(--slate-900);">
            ${formatRupiah(order.totalAmount)}
          </span>
          <button class="btn btn-secondary btn-sm btn-advance-order" data-id="${order.id}">
            🔄 Ubah / Majukan Status
          </button>
        </div>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
          <div style="font-weight: 700; color: var(--slate-900); font-size: 0.92rem;">${order.productTitle}</div>
          <div style="font-size: 0.8rem; color: var(--slate-600);">Partner Transaksi: ${order.partnerName} | Rincian: ${order.quantitySummary}</div>
        </div>
        <div style="font-size: 0.78rem; color: var(--slate-500);">
          Snapshot Terkunci: Subtotal ${formatRupiah(order.subtotal)} + Ongkir ${formatRupiah(order.shippingCost)}
        </div>
      </div>

      <div class="order-stepper-track">
        <div class="order-step-point ${s1}">
          <div class="order-step-circle">1</div>
          <span class="order-step-text">Menunggu Konfirmasi</span>
        </div>
        <div class="order-step-point ${s2}">
          <div class="order-step-circle">2</div>
          <span class="order-step-text">Dikonfirmasi</span>
        </div>
        <div class="order-step-point ${s3}">
          <div class="order-step-circle">3</div>
          <span class="order-step-text">Diproses / Disiapkan</span>
        </div>
        <div class="order-step-point ${s4}">
          <div class="order-step-circle">4</div>
          <span class="order-step-text">Selesai</span>
        </div>
      </div>
    `;

    container.appendChild(card);
  });
}

// -------------------------------------------------------------
// MODALS & ORDER CREATION
// -------------------------------------------------------------
function initModals() {
  const overlay = document.getElementById('modalOverlay');

  function openModal(dialogId) {
    if (!overlay) return;
    overlay.classList.add('active');
    document.querySelectorAll('.modal-box').forEach(b => b.style.display = 'none');
    const target = document.getElementById(dialogId);
    if (target) target.style.display = 'flex';
  }

  function closeModal() {
    if (!overlay) return;
    overlay.classList.remove('active');
    document.querySelectorAll('.modal-box').forEach(b => b.style.display = 'none');
  }

  document.querySelectorAll('[data-modal-dismiss]').forEach(btn => {
    btn.addEventListener('click', closeModal);
  });

  if (overlay) {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) closeModal();
    });
  }

  // Open modal triggers
  const btnOpenPlan = document.getElementById('btnOpenAddPlanModal');
  if (btnOpenPlan) btnOpenPlan.addEventListener('click', () => openModal('modalAddFarmPlan'));

  const btnOpenHarvest = document.getElementById('btnOpenAddHarvestModal');
  if (btnOpenHarvest) btnOpenHarvest.addEventListener('click', () => openModal('modalAddHarvestRecord'));

  // Form Submit: Tambah Rencana Tanam (perencanaan-lahan.html)
  const formPlan = document.getElementById('formAddFarmPlan');
  if (formPlan) {
    formPlan.addEventListener('submit', (e) => {
      e.preventDefault();
      const farmName = document.getElementById('planFarmSelect').value;
      const commodity = document.getElementById('planCommodityInput').value;
      const area = parseFloat(document.getElementById('planAreaInput').value) || 0.5;
      const date = document.getElementById('planHarvestDateInput').value;

      AppState.data.farmPlans.push({
        id: Date.now(),
        farmId: 1,
        farmName: farmName,
        commodity: commodity,
        plannedArea: area,
        areaUnit: 'Ha',
        startDate: new Date().toISOString().split('T')[0],
        expectedHarvestDate: date || '2027-02-15',
        status: 'Rencana Baru',
        totalEstimatedCost: 0,
        actualCost: 0
      });

      AppState.save();
      renderPlansTable();
      closeModal();
      showNotification(`Rencana pertanian "${commodity}" berhasil dibuat!`, 'success');
    });
  }

  // Form Submit: Catat Panen Baru (catatan-panen.html)
  const formHarvest = document.getElementById('formAddHarvestRecord');
  if (formHarvest) {
    formHarvest.addEventListener('submit', (e) => {
      e.preventDefault();
      const commodity = document.getElementById('harvestCommodityInput').value;
      const qty = parseFloat(document.getElementById('harvestQtyInput').value) || 100;
      const grade = document.getElementById('harvestGradeSelect').value;

      AppState.data.harvestRecords.push({
        id: Date.now(),
        farmPlanId: 1,
        commodity: commodity,
        planName: 'Lahan Sukabumi Subur',
        harvestDate: new Date().toISOString().split('T')[0],
        quantityTotal: qty,
        quantityAllocated: 0,
        quantityAvailable: qty,
        unit: 'Kg',
        grade: grade,
        qualityNotes: 'Catatan hasil panen lapangan terverifikasi.',
        isListed: false
      });

      AppState.save();
      renderHarvestTable();
      closeModal();
      showNotification(`Panen ${qty} Kg ${commodity} berhasil dicatat!`, 'success');
    });
  }

  // Global click delegator
  document.addEventListener('click', (e) => {
    // Order Supply Item
    const supplyBtn = e.target.closest('.btn-order-supply');
    if (supplyBtn) {
      const id = parseInt(supplyBtn.dataset.id, 10);
      const prod = AppState.data.products.find(p => p.id === id);
      if (prod) {
        document.getElementById('checkoutItemTitle').textContent = prod.name;
        document.getElementById('checkoutPartnerName').textContent = prod.supplierName;
        document.getElementById('checkoutUnitPrice').textContent = `${formatRupiah(prod.price)} / ${prod.unit}`;
        document.getElementById('checkoutStockAvailable').textContent = `${prod.stock} ${prod.unit}`;
        
        const qtyInp = document.getElementById('checkoutQtyInput');
        qtyInp.value = 1;
        qtyInp.max = prod.stock;

        updateOrderSnapshot(prod.price, 1);

        document.getElementById('btnSubmitOrderCheckout').dataset.itemId = prod.id;
        document.getElementById('btnSubmitOrderCheckout').dataset.type = 'supply';

        openModal('modalCheckoutOrder');
      }
    }

    // Order Harvest Produce
    const harvestBtn = e.target.closest('.btn-order-harvest');
    if (harvestBtn) {
      const id = parseInt(harvestBtn.dataset.id, 10);
      const item = AppState.data.harvestListings.find(h => h.id === id);
      if (item) {
        document.getElementById('checkoutItemTitle').textContent = item.title;
        document.getElementById('checkoutPartnerName').textContent = `${item.farmerName} (Petani)`;
        document.getElementById('checkoutUnitPrice').textContent = `${formatRupiah(item.pricePerUnit)} / ${item.unit}`;
        document.getElementById('checkoutStockAvailable').textContent = `${item.quantityAvailable} ${item.unit}`;

        const qtyInp = document.getElementById('checkoutQtyInput');
        qtyInp.value = item.minOrder || 10;
        qtyInp.max = item.quantityAvailable;

        updateOrderSnapshot(item.pricePerUnit, item.minOrder || 10);

        document.getElementById('btnSubmitOrderCheckout').dataset.itemId = item.id;
        document.getElementById('btnSubmitOrderCheckout').dataset.type = 'sales';

        openModal('modalCheckoutOrder');
      }
    }

    // Advance Order Lifecycle (PRD 8.1)
    const advanceBtn = e.target.closest('.btn-advance-order');
    if (advanceBtn) {
      const orderId = advanceBtn.dataset.id;
      const order = AppState.data.orders.find(o => o.id === orderId);
      if (order) {
        if (order.statusCode < 4) {
          order.statusCode++;
          const statusMap = {
            1: 'Menunggu konfirmasi',
            2: 'Dikonfirmasi',
            3: 'Diproses / disiapkan',
            4: 'Selesai'
          };
          order.statusLabel = statusMap[order.statusCode];
          AppState.save();
          renderOrdersView();
          showNotification(`Status ${order.id} berhasil dimajukan: "${order.statusLabel}"`, 'success');
        } else {
          showNotification(`Pesanan ${order.id} telah mencapai status akhir (Selesai)`, 'info');
        }
      }
    }
  });

  const qtyInput = document.getElementById('checkoutQtyInput');
  if (qtyInput) {
    qtyInput.addEventListener('input', () => {
      const submitBtn = document.getElementById('btnSubmitOrderCheckout');
      const type = submitBtn.dataset.type;
      const id = parseInt(submitBtn.dataset.itemId, 10);
      let unitPrice = 0;

      if (type === 'supply') {
        const prod = AppState.data.products.find(p => p.id === id);
        if (prod) unitPrice = prod.price;
      } else {
        const h = AppState.data.harvestListings.find(item => item.id === id);
        if (h) unitPrice = h.pricePerUnit;
      }

      const qty = parseFloat(qtyInput.value) || 1;
      updateOrderSnapshot(unitPrice, qty);
    });
  }

  function updateOrderSnapshot(unitPrice, qty) {
    const subtotal = unitPrice * qty;
    const estShipping = 50000;
    const total = subtotal + estShipping;

    document.getElementById('checkoutSubtotalVal').textContent = formatRupiah(subtotal);
    document.getElementById('checkoutTotalVal').textContent = formatRupiah(total);
  }

  const btnSubmitOrder = document.getElementById('btnSubmitOrderCheckout');
  if (btnSubmitOrder) {
    btnSubmitOrder.addEventListener('click', () => {
      const type = btnSubmitOrder.dataset.type;
      const id = parseInt(btnSubmitOrder.dataset.itemId, 10);
      const qty = parseFloat(qtyInput.value) || 1;

      let title = '';
      let partner = '';
      let unitPrice = 0;

      if (type === 'supply') {
        const item = AppState.data.products.find(p => p.id === id);
        if (item) {
          title = item.name;
          partner = item.supplierName;
          unitPrice = item.price;
          item.stock = Math.max(0, item.stock - qty);
        }
      } else {
        const item = AppState.data.harvestListings.find(h => h.id === id);
        if (item) {
          title = item.title;
          partner = item.farmerName;
          unitPrice = item.pricePerUnit;
          item.quantityAvailable = Math.max(0, item.quantityAvailable - qty);
        }
      }

      const newOrder = {
        id: `ORD-${type === 'supply' ? 'SP' : 'SL'}-2026-${Math.floor(100 + Math.random() * 900)}`,
        type: type,
        productTitle: title,
        partnerName: partner,
        roleInitiator: type === 'supply' ? 'farmer' : 'buyer',
        quantitySummary: `${qty} Unit`,
        subtotal: unitPrice * qty,
        shippingCost: 50000,
        totalAmount: (unitPrice * qty) + 50000,
        orderDate: new Date().toISOString().split('T')[0],
        statusCode: 1,
        statusLabel: 'Menunggu konfirmasi'
      };

      AppState.data.orders.unshift(newOrder);
      AppState.save();
      renderOrdersView();
      renderProductsMarketplace();
      renderHarvestMarketplace();
      closeModal();
      showNotification(`Pesanan #${newOrder.id} berhasil dibuat dengan snapshot harga aman!`, 'success');
    });
  }
}

// -------------------------------------------------------------
// TABLE RENDERS
// -------------------------------------------------------------
function renderPlansTable() {
  const tbody = document.getElementById('plansTableBody');
  if (!tbody) return;

  tbody.innerHTML = '';
  AppState.data.farmPlans.forEach(plan => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="cell-title">${plan.commodity}</td>
      <td>${plan.farmName}</td>
      <td>${plan.plannedArea} ${plan.areaUnit}</td>
      <td>${plan.startDate}</td>
      <td>${plan.expectedHarvestDate}</td>
      <td><span class="badge badge-info">${plan.status}</span></td>
      <td><strong>${formatRupiah(plan.totalEstimatedCost)}</strong></td>
      <td style="text-align: right;">
        <a class="btn btn-secondary btn-sm" href="estimasi-modal.html">
          Lihat Estimasi
        </a>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function renderHarvestTable() {
  const tbody = document.getElementById('harvestTableBody');
  if (!tbody) return;

  tbody.innerHTML = '';
  AppState.data.harvestRecords.forEach(h => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="cell-title">${h.commodity}</td>
      <td>${h.planName}</td>
      <td>${h.harvestDate}</td>
      <td>${h.quantityTotal} ${h.unit}</td>
      <td>
        ${h.quantityAvailable > 0
          ? `<span class="badge badge-success">${h.quantityAvailable} ${h.unit}</span>`
          : `<span class="badge badge-neutral">Habis Terjual</span>`
        }
      </td>
      <td><span class="badge badge-warning">${h.grade}</span></td>
      <td>
        ${h.isListed
          ? '<span style="font-size: 0.76rem; color: var(--primary-700); font-weight: 600;">Sudah Ditawarkan</span>'
          : `<button class="btn btn-primary btn-sm btn-publish-harvest" data-id="${h.id}">Tawarkan ke Pasar</button>`
        }
      </td>
    `;
    tbody.appendChild(tr);
  });

  tbody.querySelectorAll('.btn-publish-harvest').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = parseInt(btn.dataset.id, 10);
      const har = AppState.data.harvestRecords.find(item => item.id === id);
      if (har) {
        har.isListed = true;
        AppState.data.harvestListings.push({
          id: Date.now(),
          harvestRecordId: har.id,
          farmerName: 'Pak Budi Santoso',
          title: `${har.commodity} Segar Lapangan`,
          commodity: har.commodity,
          location: 'Sukabumi / Lembang',
          pricePerUnit: 25000,
          quantityAvailable: har.quantityAvailable,
          minOrder: 20,
          unit: 'Kg',
          grade: har.grade,
          description: 'Hasil panen segar langsung dari lahan terintegrasi AgriLink.',
          status: 'Aktif'
        });
        AppState.save();
        renderHarvestTable();
        showNotification(`Hasil panen ${har.commodity} berhasil dipublikasikan ke pasar hasil tani!`, 'success');
      }
    });
  });
}

// -------------------------------------------------------------
// APP INITIALIZATION PER PAGE
// -------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  AppState.init();
  initMobileSidebar();
  initModals();

  // Page-specific initializers based on element existence
  if (document.getElementById('plansTableBody')) renderPlansTable();
  if (document.getElementById('harvestTableBody')) renderHarvestTable();
  if (document.getElementById('estimateTableBody')) {
    renderEstimateTable();
    initEstimateInteractions();
  }
  if (document.getElementById('supplyProductsGrid')) {
    renderProductsMarketplace();
    const supplySearch = document.getElementById('supplySearchInput');
    if (supplySearch) {
      supplySearch.addEventListener('input', (e) => {
        renderProductsMarketplace('all', e.target.value);
      });
    }
    document.querySelectorAll('.supply-cat-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.supply-cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderProductsMarketplace(btn.dataset.category, supplySearch ? supplySearch.value : '');
      });
    });
  }
  if (document.getElementById('harvestMarketGrid')) {
    renderHarvestMarketplace();
    const harvestSearch = document.getElementById('harvestSearchInput');
    if (harvestSearch) {
      harvestSearch.addEventListener('input', (e) => {
        renderHarvestMarketplace(e.target.value);
      });
    }
  }
  if (document.getElementById('ordersListContainer')) {
    const roleMeta = document.body.dataset.role || 'farmer';
    renderOrdersView(roleMeta === 'supplier' ? 'supply' : roleMeta === 'buyer' ? 'sales' : 'all');
  }
});
