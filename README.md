Şu dizinlere yazma izni verilmeli:

* app/tmp
* app/log

Ardından aşağıdaki komutla bağımlılıklar yüklenmeli:

```bash
$ composer update
```

Vagrant ile çalışmak için vagrant dizininde aşağıdaki komut çalıştırılmalı. Komut çalıştırılmadan önce Vagrantfile
dosyası kontrol edilmeli ve gerekiyorsa sabit ip adresi değiştirilmeli.

```bash
$ cd vagrant
$ vagrant up
```