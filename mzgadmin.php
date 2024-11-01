<?php
if (!add_action('admin_menu', 'mzg_flak_menu')) exit ("nieteges");

function mzg_flak_menu() {
  if (!add_options_page('Z Flakera na Blog', 'Z Flakera na Blog', 'administrator', __FILE__, 'mzg_flak_options')) exit ("nieteges");
}

function mzg_flak_options() {
?>
<div class="wrap">
<?php @include_once('spamik.php') ?>
<div style="float:left; width: 65%" id="niespamik">
<h2>Ustaw sobie wtyczkę Z Flakera na Blog</h2>
<p>Jesteś teraz na stronie ustawień wtyczki Z Flakera na Blog. Bez obaw, zbyt wiele ustawiać nie trzeba</p>
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">

<tr valign="top">
<th scope="row">Twój login na Flakerze</th>
<td>http://flaker.pl/<input type="text" name="mzg_flak_login" value="<?php echo get_option('mzg_flak_login'); ?>" />
<br />Nie masz konta na Flakerze? <a href="http://flaker.pl/register" target="_blank">Załóż je teraz!</a></td>
</tr>

<tr valign="top">
<th scope="row">Tag wpisów</th>
<td>#<input type="text" name="mzg_flak_tag" value="<?php echo get_option('mzg_flak_tag'); ?>" />
<br />Tylko wpisy zawierające ten tag będą kopiowane na Twój blog. Wpisz bez znaczka hash (#). Jeśli chcesz aby pobierało wszystkie Twoje wpisy, wpisz <i>all</i>.
</td>
</tr>

<tr valign="top">
<th scope="row">Tytuł wpisu na blogu</th>
<td><input type="text" style="width:300px" name="mzg_flak_title" value="<?php echo get_option('mzg_flak_title'); ?>" />
<br />Pod tym tytułem pojawiają się Twoje wpisy z flakera na Twoim blogu (każdy wpis blogowy musi, a przynajmniej powinien, mieć jakiś tytuł)
</td>
</tr>

<tr valign="top">
<th scope="row">Umieść nowy RSS twojego bloga we Flakerze</th>
<td><p>Uwaga, uwaga. <b>Ważna sprawa</b>. Jeśli w konfiguracji konta na Flakerze podawałeś RSS feed swojego bloga, po zastosowaniu wtyczki dojdzie do pewnego rodzaju echa: wtyczka pobierze Twoje flaki z Flakera i umieści we wpisie (to jest ok), ale potem Wordpress ponownie przez RSS wyśle to do Flakera (to już nie jest ok).</p>
<p>Na szczęście nie musi tak być. Każdy wpis utworzony przez wtyczkę zostanie automatycznie przez Wordpress przypisany do kategorii, której nazwa jest taka sama, jak wpisany przez Ciebie powyżej tag (czyli '<?php echo get_option('mzg_flak_tag'); ?>'). Musisz więc podać na Flakerze nowy adres RSS z wykluczoną tą kategorią.</p>
<p>W tym celu podaj zmodyfikowany adres RSS na flakerze, z doklejonym na końcu parametrem &amp;cat-1 (pod 1 podstaw ID kategorii, do której przypisywane są Twoje flakowe wpisy). Czyli na przykład <?php bloginfo('url'); ?>/feed=rss&amp;cat=-1 </p>
<p>Inne sposoby usunięcia kategorii z RSS opisane są <a href="http://web-kreation.com/index.php/wordpress/4-ways-to-exclude-wordpress-category-from-rss-feeds/" target="_blank">na przykład tutaj</a>. Sorry za tą niedogoność, w późniejszych wersjach jakoś to rozpracuję :)</p>
</td>
</tr>

</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="mzg_flak_login,mzg_flak_tag,mzg_flak_title" />
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
<p>Ostatni Twój flak ściągnięty na bloga opublikowałeś na Flakerze: <?php echo date('d F Y, G:i:s', get_option('mzg_flak_since')) ?>. Kolejne sprawdzenie flaków odbędzie się <?php echo date('d F Y, G:i:s', wp_next_scheduled('mzg_flak_hook')) ?>.</p>
</div>
</div>
<?php
}
?>
