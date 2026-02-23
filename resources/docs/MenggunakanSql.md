## 1. SELECT dengan LIKE + ORDER BY + LIMIT (Produk)

Contoh Eloquent di `ProdukController@index` akan menghasilkan query setara:

SELECT p._, s._
FROM produks p
LEFT JOIN suppliers s ON p.id_supplier = s.id_supplier
WHERE p.nama_produk LIKE '%laptop%'
OR p.kategori LIKE '%laptop%'
OR p.spesifikasi LIKE '%laptop%'
OR s.nama_supplier LIKE '%laptop%'
ORDER BY p.created_at DESC
LIMIT 10 OFFSET 0;
