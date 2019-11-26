<div id="brshop"></div>
<h2>Новости</h2>

<?php 
defined('shoppromoauto') or die ('Загрузка...');
$results = mysql_query("SELECT * FROM news ORDER BY id DESC",$link) or die(mysql_error());
if (mysql_num_rows($results) > 0)
{
$row = mysql_fetch_array($results);
do {
echo '
<div class="b-news">
<div class="news-head">
<div class="date">
'.$row["date"].'
</div>
</div>
<div class="h-l">
<h4>
'.$row["title"].'
</h4>
<p>
'.$row["text"].'
</p>
</div>
</div>
';
} while ($row = mysql_fetch_array($results));
}
 ?>

