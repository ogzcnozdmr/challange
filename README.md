# Proje Hakkında

Proje mobil uygulamadan gelen kayıt olma, paket satın alma, Google Mock ve IOS servisi kontrolü ve abonelik durumları sorgulama işlemleriniden oluşan server-client tabalı test uygulamasıdır.

# Kurulum ve Test İşlemleri
## Gereksinimler

- PHP v8 veya daha üzeri
- Laravel v9 veya daha üzeri
- MySQL
- Laravel Horizon
- Redis
- Composer

## Kurulum
```
git clone https://github.com/ogzcnozdmr/challange.git
```
- Her hangi bir dizine indirilir.
```
composer install
```
- Proje dizininde uygulayıp ilgili gereksinimler kurulur.


Paket kurulumu gerçekleştirildikten sonra .env.example kopyalanıp .env dosyası oluşturulup Mysql, Laravel Horizon ile beraber APP_URL ayarlamasını yapıyoruz.

*APP_URL yapılandırması similasyon sırasında proje içerisinde yer alan IOS ve Android Similasyon API'lerine istekler atabilmek için zorunludur. Varsayılan olarak http://127.0.0.1:8000 şeklinde uyarlayabilirsiniz.*


```
php artisan migrate
```
- Veritabanı şemaları içeri aktarılır.

```
php artisan serve 
```
- Laravel development server başlatılır.

## Test Verileri

```
php artisan db:seed
```

Veri tabanına test verileri oluşturarak rastgele 100 adet veri üretir

## Komutlar

```
php artisan horizon
```
Laravel Horizonu başlatır

```
php artisan schedule:run
```
Kuyruk görevlerini başlatır

## Ek Bilgiler
- Durum değişiklikleri için event oluşturulmakta, hatalı olan her event için tekrar kuyruktan ayarladığımız zaman süresince kontrol yapılmaktadır.
- GoogleMock ve IOS servisleri içeriden yada dışarıdan gönderdiğimiz veriyi kontrol ederek geri dönüş sağlamaktadır.
- Veritabanı hızını artırabilmek için Mysql üzerinde arama yapacağımız kolonlarda indexleme yapılmıştır.
- Laravel Horizon’a http://127.0.0.1:8000/horizon local adresinizden ulaşabilirsiniz
- Fazla kayıtta çalışacağımız zaman Laravelin default olarak verdiği dakika başına düşen istek miktarını RouteServiceProvider dosyasından güncelleyebilirsiniz
