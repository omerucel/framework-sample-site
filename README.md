Yeni bir proje oluştururken şu komutlarla hazırlık yapılır:

```bash
$ git clone https://github.com/omerucel/framework-sample-site.git demo-project
$ cd demo-project
$ composer update
$ cd vagrant
$ vagrant up
```

vagrant up komutu çalıştırılmadan önce Vagrantfile dosyası kontrol edilmeli ve gerekiyorsa sabit ip adresi değiştirilmeli.

Proje yayına girerken aşağıdaki dizinlere yazma izni vermeyi unutmayın. Vagrant ile çalışırken bu izinler otomatik olarak verilir.

* app/tmp
* app/log