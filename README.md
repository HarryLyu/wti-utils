wti-utils
=========

Необходимо установить composer: https://getcomposer.org/download/
<pre>
curl -sS https://getcomposer.org/installer | php
</pre>

Далее
<pre>
git checkout https://github.com/HarryLyu/wti-utils.git
composer update
php src/LinguaLeo/Tools/wti/Tasks/SaveWtiCommentsTask.php WTI_API_KEY
</pre>

Все комментарии будут сохранены в папку comments/

Важный момент - все комментарии находятся в формате JSON, независимо от формата исходного файла.
