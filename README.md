# Gereksinimler

- PHP 5.3+
- php5-mcrypt
- php5-curl
- php5-gd
- php5-xdebug
- php5-json
- php5-mysql

# Kurulum

Yeni bir proje oluştururken şu komutlarla hazırlık yapılır:

```bash
$ git clone https://github.com/omerucel/starter-kit.git demo-project
$ cd demo-project
$ rm -rf .git
$ composer update
$ cp app/configs/config_development.php.sample app/configs/config_development.php
$ cp app/configs/config_production.php.sample app/configs/config_production.php
```

global.php dosyasındaki ayarlar, ilgili ortam dosyalarında ezilebilir.

Yönetim panelindeki captcha servisi için https://www.google.com/recaptcha/admin/create adresine ip adresi (development
ortamı için) ya da site adresi(production ortamı için) kaydedilmelidir. Oluşturulan private_key ve public_key
ilgili ayar dosyalarına yazılmalıdır.

# Geliştirme Sunucusu

Vagrant ya da PHP built-in server ile proje üzerinde çalışılabilir.

## PHP Built-in Server

```bash
$ APP_ENV=DEVELOPMENT php -d variables_order=EGPCS -S localhost:8080 -t public
```

## Vagrant

```bash
$ cd vagrant
$ vagrant up
$ vagrant ssh
(vagrant) $ sudo service nginx restart
```

TODO : Son değişikliklerden sonra vagrant test edilmeli.

### MySQL

- username : root
- password :

### NGINX

192.168.56.30 adresinden erişilebilir.

# Veritabanı

Aşağıdaki komutla Entity sınıfları ile veritabanı tabloları arasındaki farklar çıkartılabilir.

```bash
$ APP_ENV=DEVELOPMENT php console.php migrations:diff --configuration migrations.yml
```

Oluşan fark dosyaları ise aşağıdaki komutla veritabanına aktarılabilir.

```bash
$ APP_ENV=DEVELOPMENT php console.php migrations:migrate --configuration migrations.yml
```

# Yönetim Paneli

/admin yolu ile yönetim paneline erişilir. Aşağıdaki komutlarla roller ve kullanıcılar oluşturulur.

```bash
$ APP_ENV=DEVELOPMENT php console.php application:init-roles
$ APP_ENV=DEVELOPMENT php console.php application:create-user --username="admin" --password="admin" --role="super_user" --email="omerucel@gmail.com"
```

# Deployment

Proje yayına girerken aşağıdaki dizinlere yazma izni vermeyi unutmayın. Vagrant ile çalışırken bu izinler otomatik
olarak verilir.

* app/tmp
* app/log
* public/files

TODO : fabric üzerinden FTP ya da VPS üzerine tek bir komutla proje aktarılabilmeli.