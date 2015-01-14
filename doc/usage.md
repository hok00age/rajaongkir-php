# Panduan Penggunaan
## Instalasi
RajaOngkir PHP Client tersedia di Packagist ([hok00age/rajaongkir](https://packagist.org/packages/hok00age/rajaongkir)) sehingga dapat diinstal lewat [Composer](http://getcomposer.org/).
```bash
php composer.phar require hok00age/rajaongkir
```
## Contoh Penggunaan
### Memuat Class
```php
use hok00age\RajaOngkir;
$client = new RajaOngkir("API_KEY_ANDA");
```
### Melakukan request
```php
//Mendapatkan semua propinsi
$provinces = $client->getProvince();

//Mendapatkan semua kota
$cities = $client->getCity();

//Mendapatkan data ongkos kirim
$cost = $client->getCost(501, 114, 1000, "jne");
```
### Response
Response yang didapatkan berupa object yang didalamnya terdapat komponen berikut:
```php
//Kode status HTTP, bertipe integer (200 jika sukses)
$http_status_code = $cost->code;

//Response headers, bertipe string
$response_headers = $cost->headers;

//Response body yang sudah diubah menjadi object
$body = $cost->body;

//Response body yang belum dimodifikasi, bertipe data string (JSON)
$json_body = $cost->raw_body;
```
### Dokumentasi lebih lanjut
Silakan lihat code RajaOngkir.php, di dalamnya terdapat komentar yang dapat membantu Anda.