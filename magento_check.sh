#!/bin/bash
echo "=== Modül Durumu ==="
php bin/magento module:status | grep -i "baris"

echo -e "\n=== Cache Temizle ==="
php bin/magento cache:flush

echo -e "\n=== Setup Upgrade ==="
php bin/magento setup:upgrade

echo -e "\n=== Di Compile ==="
php bin/magento setup:di:compile

echo -e "\n✅ Tamam! Frontend sayfasına git ve sağ-tık > Inspect'le <body> tag'ını kontrol et"
