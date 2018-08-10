Hakkımda
====================

nobet.org sitesini kullanarak günün nöbetçi eczanelerini belirten bir bot yaptım.
Dileyen alıp kullanabilir, kendileri geliştirebilir, ek özellikler ekleyip güncelleyebilir.

Kullanımı
====================
```php
<?php 
require_once 'nobetci_eczane.class.php';

$eczane = new nobetci_eczane;
print_r($eczane->il('Ankara','json')); // il ve almak istediğimiz çıktı türü(json,text,array)
```

