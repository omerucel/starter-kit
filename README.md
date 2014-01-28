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