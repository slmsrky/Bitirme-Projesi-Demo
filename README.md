# Fifex - Bitirme Projesi

* Proje basit bir sohbet sitesidir.
* Projenin arayüz tasarımında HTML ve CSS dili kullanılmıştır.
* Veritabanı olarak MySQL veritabanı kullanılmıştır.
* Projede ağırlıklı olarak PHP dili, gerekli yerlerde JavaScript dili kullanılmıştır.
* Kütüphane olarak Bootstrap ve Jquery kütüphaneleri kullanılmıştır.

Projenin amacı; kullanıcının hesap oluşturması, hesaba giriş yapması, belirli ayarları değiştirebilmesi, farklı bir kullanıcı veya grup ile sohbet etmesidir.

### Ekip Üyeleri

* [Selami TİKTAŞ](https://github.com/krsez1)
* [Yusuf LAÇİN](https://github.com/YusufLacin)

### Ekip Eleştirileri

* Proje bir haftalık bir zaman aralığında kodlanmıştır. Bu durum projenin her bir aşamasında etkisini göstermiştir.
* Projede tasarım hataları vardır. Mobil uyumluluk dikkate alınarak kodlama gerçekleşmiş olsada tasarım hataları kendini göstermektedir.
* Proje basit bir tasarıma sahiptir. Estetik açısından yetersizdir.
* Backend kodlama, temiz kod yazma kurallarına göre yapılmamıştır.
* Değişken isimleri, arayüzde bulunan kısımlar, veritabanı sütun adları vb. alanlar İngilizcedir. Fakat uyarı mesajları Türkçedir.
* Yorum satırları kullanılmamıştır.
* Bireysel ve grup konuşma ekranında bulunan yenilenme bir hataya neden olmaktadır. Bu hata önceden atılan mesajlara bakmayı engellemektedir.
* Oluşturulan connection nesneleri (bağlantılar) kapatılmamaktadır. Bu durum sorunlara ve açıklara neden olabilir, sunucuya yük bindirebilir.
* Siber saldırı önlemi olarak; metin filtreleri (FILTER_SANITIZE_STRING), SQL sorgularda parametre sistemi kullanılmıştır. Bu önlemler ekip tarafından yeterli görülmemektedir.
* Proje JavaScript Enjeksiyonlarına karşı savunmasızdır.
* Projede bulunan sesli ve görüntülü konuşma için ayrı bir hizmet kullanılmaktadır.