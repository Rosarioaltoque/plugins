<?php  
header ("Content-Type:text/xml");  
?>  
<?php
//Load WordPress
header('Content-Type: text/xml');
echo 'Content-Type: text/xml'."\n\n";
echo '<?xml version="1.0">'."\n";
echo '<rss>'."\n";
echo '</rss>'."\n";
return;

$wp_root = explode("wp-content",$_SERVER["SCRIPT_FILENAME"]);
$wp_root = $wp_root[0];
if($wp_root == $_SERVER["SCRIPT_FILENAME"]) {
	$wp_root = explode("index.php",$_SERVER["SCRIPT_FILENAME"]);
	$wp_root = $wp_root[0];
}
chdir($wp_root);


if(!function_exists("add_action")) require_once(file_exists("wp-load.php")?"wp-load.php":"wp-config.php");

header('Content-Type: ' . feed_content_type('rss-http') . '."\n"; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'."\n";

/*if (@$_GET["action"] == 'GoListing') {
	@$v["action"] = 'GoListing'."\n";
}*/

$feed = '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" xmlns:georss="http://www.georss.org/georss" xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" xmlns:ymaps="http://api.maps.yahoo.com/Maps/V2/AnnotatedMaps.xsd" version="2.0">'."\n";
$feed .= '<channel>'."\n";
$feed .= '<title>Rosario al toque » Cortes y desvíos</title>'."\n";
$feed .= '<atom:link href="http://apoeylaut.rcd.pampacomcdt.com.ar/category/cortes-y-desvios/feed/" rel="self" type="application/rss+xml"/>'."\n";
$feed .= '<link>http://apoeylaut.rcd.pampacomcdt.com.ar</link>'."\n";
$feed .= '<description>Descripción de Rosario al toque aquí</description>'."\n";
$feed .= '<lastBuildDate>Tue, 22 Nov 2011 16:49:36 +0000</lastBuildDate>'."\n";
$feed .= '<language>en</language>'."\n";
$feed .= '<sy:updatePeriod>hourly</sy:updatePeriod>'."\n";
$feed .= '<sy:updateFrequency>1</sy:updateFrequency>'."\n";
$feed .= '<generator>http://wordpress.org/?v=3.2.1</generator>'."\n";

$feed .= '<item>'."\n";
$feed .= '<title>Corte/Desvio</title>'."\n";
$feed .= '<link>http://apoeylaut.rcd.pampacomcdt.com.ar/2011/11/22/cortedesvio-297/</link>'."\n";
$feed .= '<comments>http://apoeylaut.rcd.pampacomcdt.com.ar/2011/11/22/cortedesvio-297/#comments</comments>'."\n";
$feed .= '<pubDate>Tue, 22 Nov 2011 12:08:16 +0000</pubDate>'."\n";
$feed .= '<dc:creator>entetransporte</dc:creator>'."\n";
$feed .= '<category><![CDATA[ Cortes y desvíos ]]></category>'."\n";
$feed .= '<guid isPermaLink="false">http://www.etr.gov.ar/cont-noticias_vigentes2.php?id_noticia=925</guid>'."\n";
$feed .= '<description><![CDATA[Cortes y Desvios &#8230; <a href="http://0.3.rcd.pampacomcdt.com.ar/2011/09/18/24-de-mayo-cambio-de-recorrido/">Continue reading</a>]]></description>'."\n";
$feed .= '<content:encoded><![CDATA[A ra&iacute;z de producirse&nbsp; un corte total d]]></content:encoded>'."\n";
$feed .= '<wfw:commentRss>http://apoeylaut.rcd.pampacomcdt.com.ar/2011/11/22/cortedesvio-297/feed/</wfw:commentRss>'."\n";
$feed .= '<slash:comments>0</slash:comments>'."\n";
$feed .= '<georss:point>-32.931381152557606 -60.66103229360965</georss:point>'."\n";
$feed .= '<geo:lat>-32.931381152557606</geo:lat>'."\n";
$feed .= '<geo:long>-60.66103229360965</geo:long>'."\n";
$feed .= '</item>'."\n";

$feed .= '</channel>'."\n";
$feed .= '</rss>'."\n";

die($feed);
