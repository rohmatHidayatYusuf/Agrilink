<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AgriLinkSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $hashedPassword = password_hash('password', PASSWORD_BCRYPT);
        $now = date('Y-m-d H:i:s');

        // 1. SEED USERS
        $users = [
            [
                'id'           => 1,
                'nama_lengkap' => 'Pak Budi Santoso',
                'username'     => 'pakbudi',
                'email'        => 'petani@agrilink.id',
                'password'     => $hashedPassword,
                'role'         => 'petani',
                'telepon'      => '081234567890',
                'alamat'       => 'Desa Sukamaju, Kec. Cisarua, Kab. Bogor, Jawa Barat',
                'status'       => 'active',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'id'           => 2,
                'nama_lengkap' => 'CV Tani Makmur',
                'username'     => 'tanimakmur',
                'email'        => 'supplier@agrilink.id',
                'password'     => $hashedPassword,
                'role'         => 'supplier',
                'telepon'      => '081298765432',
                'alamat'       => 'Kawasan Pergudangan Niaga Pertanian No. 45, Bandung',
                'status'       => 'active',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'id'           => 3,
                'nama_lengkap' => 'Resto Segar Nusantara',
                'username'     => 'restosegar',
                'email'        => 'buyer@agrilink.id',
                'password'     => $hashedPassword,
                'role'         => 'buyer',
                'telepon'      => '081311223344',
                'alamat'       => 'Jl. Boulevard Utama Selatan No. 88, Kebayoran Baru, Jakarta Selatan',
                'status'       => 'active',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'id'           => 4,
                'nama_lengkap' => 'Admin Pengawas Sistem',
                'username'     => 'admin',
                'email'        => 'admin@agrilink.id',
                'password'     => $hashedPassword,
                'role'         => 'admin',
                'telepon'      => '081100001111',
                'alamat'       => 'AgriLink Operational HQ Lt. 4, Jakarta Pusat',
                'status'       => 'active',
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        foreach ($users as $user) {
            $existing = $db->table('users')->where('id', $user['id'])->orWhere('email', $user['email'])->get()->getRow();
            if (!$existing) {
                $db->table('users')->insert($user);
            } else {
                $db->table('users')->where('id', $existing->id)->update($user);
            }
        }

        // 2. SEED SUBSCRIPTION PLANS
        $plans = [
            [
                'id'             => 1,
                'name'           => 'Paket Akses MVP Terintegrasi',
                'price'          => 0.00,
                'billing_period' => 'selamanya',
                'features'       => 'Multi-lahan aktif, Estimasi modal tanpa batas, Akses katalog supplier, Catatan panen & pasar tani, Pelacakan pesanan 4-tahap',
                'status'         => 'active',
                'created_at'     => $now,
            ],
            [
                'id'             => 2,
                'name'           => 'Paket Premium Komersial',
                'price'          => 75000.00,
                'billing_period' => 'bulanan',
                'features'       => 'Semua fitur MVP + Analitik profitabilitas lintas musim, Ekspor PDF/Excel, Featured listing pasar hasil tani, Konsultasi agronomis',
                'status'         => 'active',
                'created_at'     => $now,
            ],
        ];

        foreach ($plans as $plan) {
            $existing = $db->table('subscription_plans')->where('id', $plan['id'])->get()->getRow();
            if (!$existing) {
                $db->table('subscription_plans')->insert($plan);
            }
        }

        // 3. SEED SUBSCRIPTIONS
        for ($u = 1; $u <= 4; $u++) {
            $subExists = $db->table('subscriptions')->where('user_id', $u)->get()->getRow();
            if (!$subExists) {
                $db->table('subscriptions')->insert([
                    'user_id'    => $u,
                    'plan_id'    => 1,
                    'status'     => 'active',
                    'start_at'   => $now,
                    'end_at'     => date('Y-m-d H:i:s', strtotime('+1 year')),
                    'created_at' => $now,
                ]);
            }
        }

        // 4. SEED FARMS (Profil Lahan Petani Pak Budi)
        $farms = [
            [
                'id'         => 1,
                'owner_id'   => 1,
                'name'       => 'Lahan Blok A - Cisarua',
                'location'   => 'Kampung Sindangrasa, Cisarua, Bogor (Sawah Irigasi Teknis)',
                'area_size'  => 1.50,
                'area_unit'  => 'Ha',
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id'         => 2,
                'owner_id'   => 1,
                'name'       => 'Lahan Blok B - Sukamaju',
                'location'   => 'Desa Sukamaju, Megamendung (Tanah Darat / Hortikultura)',
                'area_size'  => 0.80,
                'area_unit'  => 'Ha',
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($farms as $farm) {
            $existing = $db->table('farms')->where('id', $farm['id'])->get()->getRow();
            if (!$existing) {
                $db->table('farms')->insert($farm);
            }
        }

        // 5. SEED FARM PLANS (Rencana Tanam)
        $plans = [
            [
                'id'                    => 1,
                'farm_id'               => 1,
                'created_by'            => 1,
                'commodity'             => 'Padi Ciherang Unggul',
                'planned_area'          => 1.00,
                'start_date'            => date('Y-m-d', strtotime('-60 days')),
                'expected_harvest_date' => date('Y-m-d', strtotime('+30 days')),
                'status'                => 'active',
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
            [
                'id'                    => 2,
                'farm_id'               => 2,
                'created_by'            => 1,
                'commodity'             => 'Cabai Rawit Merah Prima',
                'planned_area'          => 0.50,
                'start_date'            => date('Y-m-d', strtotime('-90 days')),
                'expected_harvest_date' => date('Y-m-d', strtotime('-10 days')),
                'status'                => 'harvested',
                'created_at'            => $now,
                'updated_at'            => $now,
            ],
        ];

        foreach ($plans as $p) {
            $existing = $db->table('farm_plans')->where('id', $p['id'])->get()->getRow();
            if (!$existing) {
                $db->table('farm_plans')->insert($p);
            }
        }

        // 6. SEED PRODUCTS (Katalog Saprotan CV Tani Makmur)
        $products = [
            [
                'id'          => 1,
                'supplier_id' => 2,
                'name'        => 'Benih Padi Ciherang Sertifikasi Unggul',
                'category'    => 'Benih',
                'description' => 'Benih padi bersertifikat resmi Balai Benih, potensi hasil panen 8.5 ton/ha, tahan rebah.',
                'price'       => 85000.00,
                'unit'        => 'zak 5kg',
                'stock'       => 150.00,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 2,
                'supplier_id' => 2,
                'name'        => 'Pupuk Organik Kompos Granul Super',
                'category'    => 'Pupuk',
                'description' => 'Kompos matang kaya unsur hara makro & mikro, memperbaiki struktur tanah dan daya simpan air.',
                'price'       => 45000.00,
                'unit'        => 'karung 25kg',
                'stock'       => 300.00,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 3,
                'supplier_id' => 2,
                'name'        => 'Pupuk NPK Mutiara 16-16-16 Biru',
                'category'    => 'Pupuk',
                'description' => 'Formula seimbang cepat larut, merangsang pertumbuhan vegetatif dan pengisian gabah.',
                'price'       => 185000.00,
                'unit'        => 'sak 50kg',
                'stock'       => 80.00,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 4,
                'supplier_id' => 2,
                'name'        => 'Insektisida Nabati Ekstrak Mimba 500ml',
                'category'    => 'Pestisida',
                'description' => 'Pengendali hama wereng dan penggerek batang ramah lingkungan tanpa residu kimia berbahaya.',
                'price'       => 65000.00,
                'unit'        => 'botol',
                'stock'       => 75.00,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'id'          => 5,
                'supplier_id' => 2,
                'name'        => 'Knapsack Sprayer Elektrik 16 Liter 2in1',
                'category'    => 'Alsintan',
                'description' => 'Alat semprot elektrik bertenaga baterai rechargeable tahan 8 jam pemakaian intensif.',
                'price'       => 480000.00,
                'unit'        => 'unit',
                'stock'       => 20.00,
                'status'      => 'active',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ];

        foreach ($products as $pr) {
            $existing = $db->table('products')->where('id', $pr['id'])->get()->getRow();
            if (!$existing) {
                $db->table('products')->insert($pr);
            }
        }

        // 7. SEED ESTIMATES & ITEMS
        $estExists = $db->table('estimates')->where('id', 1)->get()->getRow();
        if (!$estExists) {
            $db->table('estimates')->insert([
                'id'                   => 1,
                'farm_plan_id'         => 1,
                'created_by'           => 1,
                'total_estimated_cost' => 1955000.00,
                'status'               => 'final',
                'notes'                => 'Estimasi awal sarana produksi penanaman padi 1 hektar blok A.',
                'created_at'           => $now,
                'updated_at'           => $now,
            ]);

            $estItems = [
                [
                    'estimate_id'          => 1,
                    'product_id'           => 1,
                    'item_name'            => 'Benih Padi Ciherang Sertifikasi Unggul',
                    'category'             => 'Benih',
                    'quantity'             => 5.00,
                    'unit'                 => 'zak 5kg',
                    'estimated_unit_price' => 85000.00,
                    'subtotal'             => 425000.00,
                ],
                [
                    'estimate_id'          => 1,
                    'product_id'           => 2,
                    'item_name'            => 'Pupuk Organik Kompos Granul Super',
                    'category'             => 'Pupuk',
                    'quantity'             => 10.00,
                    'unit'                 => 'karung 25kg',
                    'estimated_unit_price' => 45000.00,
                    'subtotal'             => 450000.00,
                ],
                [
                    'estimate_id'          => 1,
                    'product_id'           => 3,
                    'item_name'            => 'Pupuk NPK Mutiara 16-16-16 Biru',
                    'category'             => 'Pupuk',
                    'quantity'             => 4.00,
                    'unit'                 => 'sak 50kg',
                    'estimated_unit_price' => 185000.00,
                    'subtotal'             => 740000.00,
                ],
                [
                    'estimate_id'          => 1,
                    'product_id'           => 4,
                    'item_name'            => 'Insektisida Nabati Ekstrak Mimba 500ml',
                    'category'             => 'Pestisida',
                    'quantity'             => 4.00,
                    'unit'                 => 'botol',
                    'estimated_unit_price' => 65000.00,
                    'subtotal'             => 260000.00,
                ],
                [
                    'estimate_id'          => 1,
                    'product_id'           => null,
                    'item_name'            => 'Ongkos Pengolahan Lahan & Traktor',
                    'category'             => 'Tenaga Kerja',
                    'quantity'             => 1.00,
                    'unit'                 => 'paket',
                    'estimated_unit_price' => 80000.00,
                    'subtotal'             => 80000.00,
                ],
            ];
            $db->table('estimate_items')->insertBatch($estItems);
        }

        // 8. SEED HARVEST RECORDS
        $harvestExists = $db->table('harvest_records')->where('id', 1)->get()->getRow();
        if (!$harvestExists) {
            $db->table('harvest_records')->insert([
                'id'               => 1,
                'farm_plan_id'     => 2,
                'farmer_id'        => 1,
                'commodity'        => 'Cabai Rawit Merah Prima',
                'harvest_date'     => date('Y-m-d', strtotime('-10 days')),
                'quantity'         => 850.00,
                'quantity_sold'    => 200.00,
                'unit'             => 'kg',
                'quality_notes'    => 'Grade Super, Segar Petik Pagi, Warna Merah Cerah',
                'storage_location' => 'Gudang Penyimpanan Dingin Lahan Blok B',
                'status'           => 'listed',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }

        // 9. SEED HARVEST LISTINGS (Pasar Hasil Tani)
        $listingExists = $db->table('harvest_listings')->where('id', 1)->get()->getRow();
        if (!$listingExists) {
            $db->table('harvest_listings')->insert([
                'id'                 => 1,
                'harvest_record_id'  => 1,
                'seller_id'          => 1,
                'title'              => 'Cabai Rawit Merah Segar Petik Pagi Grade A',
                'description'        => 'Cabai rawit merah kualitas prima hasil kebun Megamendung. Pedas maksimal, bebas residu pestisida kimia, siap kirim ke restoran & pasar.',
                'price_per_unit'     => 38000.00,
                'quantity_available' => 650.00,
                'unit'               => 'kg',
                'min_order'          => 10.00,
                'status'             => 'active',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // 10. SEED SUPPLY ORDERS (Pesanan Saprotan Petani ke Supplier)
        $supOrderExists = $db->table('supply_orders')->where('id', 1)->get()->getRow();
        if (!$supOrderExists) {
            $db->table('supply_orders')->insert([
                'id'               => 1,
                'order_number'     => 'SUP-202611-001',
                'buyer_id'         => 1,
                'supplier_id'      => 2,
                'subtotal'         => 875000.00,
                'shipping_cost'    => 45000.00,
                'total_amount'     => 920000.00,
                'shipping_address' => 'Gudang Tani Blok A, Cisarua, Bogor',
                'notes'            => 'Kirim pagi hari sebelum jam 10.',
                'status'           => 'dikonfirmasi',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at'       => $now,
            ]);

            $db->table('supply_order_items')->insert([
                'order_id'              => 1,
                'product_id'            => 1,
                'product_name_snapshot' => 'Benih Padi Ciherang Sertifikasi Unggul',
                'quantity'              => 5.00,
                'unit'                  => 'zak 5kg',
                'unit_price_snapshot'   => 85000.00,
                'subtotal'              => 425000.00,
            ]);

            $db->table('supply_order_items')->insert([
                'order_id'              => 1,
                'product_id'            => 2,
                'product_name_snapshot' => 'Pupuk Organik Kompos Granul Super',
                'quantity'              => 10.00,
                'unit'                  => 'karung 25kg',
                'unit_price_snapshot'   => 45000.00,
                'subtotal'              => 450000.00,
            ]);

            $db->table('order_status_histories')->insert([
                'order_type'      => 'supply',
                'order_id'        => 1,
                'previous_status' => 'menunggu_konfirmasi',
                'new_status'      => 'dikonfirmasi',
                'notes'           => 'Pesanan diterima dan stok telah dialokasikan.',
                'changed_by'      => 2,
                'created_at'      => $now,
            ]);
        }

        // 11. SEED SALES ORDERS (Pesanan Hasil Tani Buyer ke Petani)
        $salesOrderExists = $db->table('sales_orders')->where('id', 1)->get()->getRow();
        if (!$salesOrderExists) {
            $db->table('sales_orders')->insert([
                'id'               => 1,
                'order_number'     => 'ORD-202611-001',
                'buyer_id'         => 3,
                'seller_id'        => 1,
                'subtotal'         => 7600000.00,
                'shipping_cost'    => 150000.00,
                'total_amount'     => 7750000.00,
                'delivery_address' => 'Resto Segar Nusantara, Jl. Boulevard Selatan No. 88, Kebayoran Baru, Jakarta Selatan',
                'notes'            => 'Harap dikemas dalam keranjang berlubang agar cabai tetap segar.',
                'status'           => 'diproses',
                'created_at'       => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at'       => $now,
            ]);

            $db->table('sales_order_items')->insert([
                'order_id'              => 1,
                'listing_id'            => 1,
                'product_name_snapshot' => 'Cabai Rawit Merah Segar Petik Pagi Grade A',
                'quantity'              => 200.00,
                'unit'                  => 'kg',
                'unit_price_snapshot'   => 38000.00,
                'subtotal'              => 7600000.00,
            ]);

            $db->table('order_status_histories')->insert([
                'order_type'      => 'sales',
                'order_id'        => 1,
                'previous_status' => 'dikonfirmasi',
                'new_status'      => 'diproses',
                'notes'           => 'Cabai sedang disortir dan dipacking ke dalam keranjang.',
                'changed_by'      => 1,
                'created_at'      => $now,
            ]);
        }
    }
}
