= Gereklilikler =

**PHP 5.4+ gerektirir.**

= Projenin Hazırlanması =

Yeni bir proje oluştururken aşağıdaki komutlarla hazırlık yapılır. Vagrant kutusu çalıştırılmadan önce Vagrantfile
dosyasındaki $web_server_id ve $vagrant_module_path ayarları kontrol edilmelidir. $vagrant_module_path ayarı için
https://github.com/omerucel/vagrant-shell-modules projesi kurulu değilse kurulmalıdır.

```bash
$ git clone https://github.com/omerucel/starter-kit.git demo-project
$ cd demo-project
$ rm -rf .git
$ composer update
$ cp app/configs/development.php.sample app/configs/development.php
$ cp app/configs/production.php.sample app/configs/production.php
$ cp app/configs/testing.php.sample app/configs/testing.php
$ cp buildtime-conf.xml.sample buildtime-conf.xml
$ cd vagrant
$ vagrant up
$ vagrant ssh
(vagrant)$ sudo service nginx restart
```

Proje yayına girerken aşağıdaki dizinlere yazma izni vermeyi unutmayın. Vagrant ile çalışırken bu izinler otomatik
olarak verilir.

* app/tmp
* app/log

Yönetim panelindeki captcha servisi için https://www.google.com/recaptcha/admin/create adresine ip adresi
(development ortamı için) ya da site adresi(production ortamı için) kaydedilmelidir. Oluşturulan private_key ve
public_key ilgili ayar dosyalarına yazılmalıdır.

= Komutlar =

app/commands dizinine eklenecek komutlar şu şekilde çalıştırılır:

```bash
$ php app/commands/test.php -e development
```

= Erişim =

== MySQL ==

- username: root
- password:

== nginx & apache ==

192.168.56.30 adresi üzerinden proje yayınlanmaktadır.

= Veritabanı Ayarı =

Veritabanı adı değiştirilmek istenirse aşağıdaki dosyalarda bulunan **starterkit** ismi güncellenmelidir. Bu
dosyalar güncellendikten sonra *migration* komutları çalıştırılmalıdır.

- /app/configs/global.php (pdo dbname)
- /buildtime-conf.xml
- /schema.xml

= Propel =

Modelleri oluşturmak için aşağıdaki komut çalıştırılmalıdır.

```bash
$ vendor/bin/propel model:build --output-dir=src/
```

== Migration ==

Migration özelliği, veritabanı hazır olduktan sonra kullanılabilmektedir. Bunun için ilk olarak yukarıda anlatılan
**propel sql:insert** ile hazırlık yapılmalıdır. Veritabanı hazırlandıktan sonra, schema.xml değişikliklerinde şu komutlar
çalıştırılmalıdır.

```bash
$ vendor/bin/propel migration:diff
$ vendor/bin/propel migration:migrate
```

== SQL ==

Eğer veritabanı şemasının sql dosyasına ihtiyaç duyulursa ya da sql dosyası çalıştırılmak istenirse
aşağıdaki komutlar kullanılmalıdır:

SQL dosyalarını oluşturmak için:

```bash
$ vendor/bin/propel sql:build
```

SQL dosyalarını veritabanına aktarmak için:

```bash
$ vendor/bin/propel sql:insert --connection="starterkit=mysql:host=127.0.0.1;dbname=starterkit;user=root" --verbose
```