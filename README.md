Yeni bir proje oluştururken şu komutlarla hazırlık yapılır:

```bash
$ git clone https://github.com/omerucel/framework-sample-site.git demo-project
$ cd demo-project
$ composer update
$ cp app/configs/development.php.sample app/configs/development.php
$ cp app/configs/production.php.sample app/configs/production.php
$ cd vagrant
$ vagrant up
```

vagrant up komutu çalıştırılmadan önce Vagrantfile dosyası kontrol edilmeli ve gerekiyorsa sabit ip adresi değiştirilmeli.

Projenin geliştirici ortamında çalışabilmesi için aşağıdaki komutlar çalıştırılmalıdır:

```bash
$ composer update
$ cp app/configs/development.php.sample app/configs/development.php
```

Proje yayına girerken aşağıdaki dizinlere yazma izni vermeyi unutmayın. Vagrant ile çalışırken bu izinler otomatik olarak verilir.

* app/tmp
* app/log

Yönetim panelindeki captcha servisi için https://www.google.com/recaptcha/admin/create adresine ip adresi (development ortamı için) ya da site adresi(production ortamı için) kaydedilmelidir. Oluşturulan private_key ve public_key ilgili ayar dosyalarına yazılmalıdır.

Yönetim paneli için aşağıdaki sql sorgusu ile yeni kullanıcı oluşturulabilir:

```sql
INSERT INTO user(username, password, email) VALUES("admin", SHA1("admin"), "admin@admin.com");
INSERT INTO admin_user(user_id) VALUES(1);
```