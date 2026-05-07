-- Data Dummy untuk Tabel transactions (Hari Ini dan Kemarin)
-- Silakan jalankan di console MySQL / phpMyAdmin Anda
-- Pastikan tabel sudah ter-migrate sebelumnya

INSERT INTO transactions (
    transaction_id, item, date_time, period_day, weekday_weekend, 
    quantity, harga_satuan, subtotal, kasir, metode_bayar, created_at, updated_at
) VALUES
-- Transaksi Hari Ini (Ganti DATE(NOW()) jika perlu)
(1001, 'Roti Tawar', CONCAT(CURDATE(), ' 08:15:00'), 'morning', 'weekday', 2, 15000, 30000, 'Sari', 'cash', NOW(), NOW()),
(1001, 'Croissant', CONCAT(CURDATE(), ' 08:15:00'), 'morning', 'weekday', 3, 18000, 54000, 'Sari', 'cash', NOW(), NOW()),
(1002, 'Donat Cokelat', CONCAT(CURDATE(), ' 10:30:00'), 'morning', 'weekday', 5, 8000, 40000, 'Budi', 'qris', NOW(), NOW()),
(1003, 'Baguette', CONCAT(CURDATE(), ' 12:45:00'), 'afternoon', 'weekday', 1, 22000, 22000, 'Sari', 'debit', NOW(), NOW()),
(1004, 'Roti Keju', CONCAT(CURDATE(), ' 15:20:00'), 'afternoon', 'weekday', 4, 12000, 48000, 'Budi', 'cash', NOW(), NOW()),
(1005, 'Croissant', CONCAT(CURDATE(), ' 17:10:00'), 'evening', 'weekday', 2, 18000, 36000, 'Sari', 'qris', NOW(), NOW()),
(1006, 'Roti Tawar', CONCAT(CURDATE(), ' 18:30:00'), 'evening', 'weekday', 1, 15000, 15000, 'Budi', 'cash', NOW(), NOW()),

-- Transaksi Kemarin
(998, 'Baguette', CONCAT(CURDATE() - INTERVAL 1 DAY, ' 09:00:00'), 'morning', 'weekday', 2, 22000, 44000, 'Budi', 'cash', NOW(), NOW()),
(998, 'Roti Keju', CONCAT(CURDATE() - INTERVAL 1 DAY, ' 09:00:00'), 'morning', 'weekday', 1, 12000, 12000, 'Budi', 'cash', NOW(), NOW()),
(999, 'Donat Cokelat', CONCAT(CURDATE() - INTERVAL 1 DAY, ' 14:15:00'), 'afternoon', 'weekday', 10, 8000, 80000, 'Sari', 'qris', NOW(), NOW()),
(1000, 'Croissant', CONCAT(CURDATE() - INTERVAL 1 DAY, ' 16:45:00'), 'evening', 'weekday', 4, 18000, 72000, 'Budi', 'debit', NOW(), NOW());


-- Data Dummy untuk Tabel forecasts (Prediksi Besok)
INSERT INTO forecasts (
    tanggal_prediksi, mode, prediksi_transaksi, fitur, sumber, generated_at, created_at, updated_at
) VALUES
(CURDATE() + INTERVAL 1 DAY, 'total', 185, '{"lag_1":150,"lag_2":140}', 'dataset', NOW(), NOW(), NOW()),
(CURDATE() + INTERVAL 1 DAY, 'Roti Tawar', 82, '{"lag_1":70,"lag_2":65}', 'kasir', NOW(), NOW(), NOW()),
(CURDATE() + INTERVAL 1 DAY, 'Croissant', 45, '{"lag_1":40,"lag_2":38}', 'kasir', NOW(), NOW(), NOW()),
(CURDATE() + INTERVAL 1 DAY, 'Donat Cokelat', 38, '{"lag_1":35,"lag_2":30}', 'kasir', NOW(), NOW(), NOW()),
(CURDATE() + INTERVAL 1 DAY, 'Baguette', 20, '{"lag_1":18,"lag_2":20}', 'kasir', NOW(), NOW(), NOW());
