wti-utils
=========

Необходимо установить composer: https://getcomposer.org/download/

## Установка:
<pre>
curl -sS https://getcomposer.org/installer | php
git checkout https://github.com/HarryLyu/wti-utils.git
composer update
</pre>

## Использование:

### Сохранение комментариев:
<pre>
php src/LinguaLeo/Tools/wti/Tasks/SaveWtiCommentsTask.php WTI_API_KEY
</pre>
Все комментарии будут сохранены в папку comments/.
Важный момент - все комментарии находятся в формате JSON, независимо от формата исходного файла.

### Выгрузка сегментов, которые находятся не в файлах:
<pre>
php src/LinguaLeo/Tools/wti/Tasks/SaveSegmentsNotInFileTask.php WTI_API_KEY
</pre>

### Отправка в WTI сегментов, которые находятся не в файлах:
<pre>
php src/LinguaLeo/Tools/wti/Tasks/UpdateSegmentsNotInFileTask.php  WTI_API_KEY
</pre>