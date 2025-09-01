#!/bin/bash

echo "🔄 Ожидание готовности базы данных..."

until mysql -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USER" -p"$DB_PASSWORD" --ssl=0 -e "SELECT 1" >/dev/null 2>&1; do
    echo "База данных еще не готова, ожидание..."
    sleep 2
done

echo "Подключение к базе данных успешно"

echo "Выполнение миграций..."
cd /app
echo "yes" | php yii migrate --interactive=0

echo "Миграции выполнены!"
