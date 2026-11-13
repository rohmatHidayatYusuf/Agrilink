<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgriLinkTables extends Migration
{
    public function up()
    {
        // 1. TABEL: users
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['petani', 'supplier', 'buyer', 'admin'],
                'default'    => 'petani',
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'alamat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'suspended'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users', true);

        // 2. TABEL: farms (Profil Lahan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'owner_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'location' => [
                'type' => 'TEXT',
            ],
            'area_size' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'area_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'Ha',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'archived'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('owner_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('farms', true);

        // 3. TABEL: farm_plans (Rencana Tanam Komoditas)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'farm_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'commodity' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'planned_area' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'expected_harvest_date' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'active', 'harvested', 'archived'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('farm_id', 'farms', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('farm_plans', true);

        // 4. TABEL: estimates (Estimasi Modal & Anggaran Tanam)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'farm_plan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'total_estimated_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'final', 'archived'],
                'default'    => 'final',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('farm_plan_id', 'farm_plans', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('estimates', true);

        // 5. TABEL: products (Katalog Saprotan Supplier)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Benih',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'stock' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'moderated'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('supplier_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('products', true);

        // 6. TABEL: estimate_items (Rincian Item Kebutuhan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'estimate_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'Benih',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 1.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'estimated_unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('estimate_id', 'estimates', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'RESTRICT');
        $this->forge->createTable('estimate_items', true);

        // 7. TABEL: supply_orders (Pemesanan Saprotan oleh Petani)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'buyer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'supplier_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'shipping_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'shipping_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu_konfirmasi', 'dikonfirmasi', 'diproses', 'selesai', 'dibatalkan', 'ditolak'],
                'default'    => 'menunggu_konfirmasi',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('supplier_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('supply_orders', true);

        // 8. TABEL: supply_order_items (Rincian Pesanan Saprotan dengan Snapshot Harga)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'product_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'product_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 1.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'unit_price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'supply_orders', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'SET NULL', 'RESTRICT');
        $this->forge->createTable('supply_order_items', true);

        // 9. TABEL: harvest_records (Pencatatan Panen Fisik)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'farm_plan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'farmer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'commodity' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'harvest_date' => [
                'type' => 'DATE',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'quantity_sold' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'quality_notes' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'storage_location' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['stored', 'listed', 'archived'],
                'default'    => 'stored',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('farm_plan_id', 'farm_plans', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('farmer_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('harvest_records', true);

        // 10. TABEL: harvest_listings (Etalase Pasar Hasil Tani)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'harvest_record_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'seller_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price_per_unit' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'quantity_available' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'min_order' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 1.00,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive', 'sold_out', 'moderated'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('harvest_record_id', 'harvest_records', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('harvest_listings', true);

        // 11. TABEL: sales_orders (Pesanan Komoditas oleh Buyer ke Petani)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'buyer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'seller_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'shipping_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'total_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
            'delivery_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu_konfirmasi', 'dikonfirmasi', 'diproses', 'selesai', 'dibatalkan', 'ditolak'],
                'default'    => 'menunggu_konfirmasi',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('buyer_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('seller_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('sales_orders', true);

        // 12. TABEL: sales_order_items (Rincian Item Pesanan Hasil Tani dengan Snapshot)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'listing_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'product_name_snapshot' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 1.00,
            ],
            'unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'kg',
            ],
            'unit_price_snapshot' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '14,2',
                'default'    => 0.00,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('order_id', 'sales_orders', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('listing_id', 'harvest_listings', 'id', 'SET NULL', 'RESTRICT');
        $this->forge->createTable('sales_order_items', true);

        // 13. TABEL: order_status_histories (Audit Trail Perubahan Status Pesanan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'order_type' => [
                'type'       => 'ENUM',
                'constraint' => ['supply', 'sales'],
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'previous_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'changed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('changed_by', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('order_status_histories', true);

        // 14. TABEL: admin_audit_logs (Jejak Audit Aksi Administratif)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'admin_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'entity_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'entity_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'details' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('admin_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('admin_audit_logs', true);

        // 15. TABEL: subscription_plans (Definisi Paket Langganan)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,2',
                'default'    => 0.00,
            ],
            'billing_period' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'bulanan',
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default'    => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('subscription_plans', true);

        // 16. TABEL: subscriptions (Status Langganan Pengguna)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'plan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'expired', 'cancelled'],
                'default'    => 'active',
            ],
            'start_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'end_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('plan_id', 'subscription_plans', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('subscriptions', true);
        $this->forge->dropTable('subscription_plans', true);
        $this->forge->dropTable('admin_audit_logs', true);
        $this->forge->dropTable('order_status_histories', true);
        $this->forge->dropTable('sales_order_items', true);
        $this->forge->dropTable('sales_orders', true);
        $this->forge->dropTable('harvest_listings', true);
        $this->forge->dropTable('harvest_records', true);
        $this->forge->dropTable('supply_order_items', true);
        $this->forge->dropTable('supply_orders', true);
        $this->forge->dropTable('estimate_items', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('estimates', true);
        $this->forge->dropTable('farm_plans', true);
        $this->forge->dropTable('farms', true);
        $this->forge->dropTable('users', true);
    }
}
