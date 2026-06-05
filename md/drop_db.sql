USE db_jasa_dekorasi_pernikahan;

-- Matikan foreign key check sementara untuk menghindari error restraint
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS tb_pesan;
DROP TABLE IF EXISTS tb_testimoni;
DROP TABLE IF EXISTS tb_portofolio;
DROP TABLE IF EXISTS tb_paket;
DROP TABLE IF EXISTS tb_kategori;
DROP TABLE IF EXISTS tb_admin;

SET FOREIGN_KEY_CHECKS = 1;