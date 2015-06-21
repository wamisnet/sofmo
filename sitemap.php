<?php
/*********************************************
シンプルサイトマップ 1.0
検索エンジン用SitemapsのXMLを自動生成します。
---------------------------------------------
(C)検さぽ！ http://www.kensapo.com/
Sitemaps登録方法 http://www.kensapo.com/sitemaps.html
---------------------------------------------
【使い方】
1.下記の設定を行い、サーバにアップロードします。
2.本スクリプトsitemap.phpにアクセスし、XMLが表示されることを確認します。
3.sitemap.phpを検索エンジンに登録します。登録方法は上記サイトをご覧ください。
【利用規約】
※スクリプトはフリーウェアです。ただし、著作権は放棄していません。
※スクリプトによって何らかの不利益、損害が生じても一切の責任を負いません。あらかじめご了承願います。
【リンクのお願い】
※下記サイトへリンクを貼っていただけると幸いです。
↓リンク用タグ
<a href="http://www.kensapo.com/" target="_blank">検索エンジン登録方法 検さぽ！</a>
*********************************************/
//---------------設定ここから---------------

//サイトURL(最後の/あり)
define('URL', "http://sofmo.pw/");

//読み込むファイルタイプ(|で区切り)
define('EXT', "htm|html");

//ファイル名 1=昇順 or 0=降順
define('SORT', "1");

//除外するファイル名を指定(全フォルダ共通(|で区切り) or 0=指定しない)
define('NONE', "0");

//取得するファイル名を指定(ルートフォルダ(|で区切り)これ以外は除外 or 0=指定しない)
define('ONLYL', "0");

//取得するファイル名を指定(サブフォルダ(|で区切り)これ以外は除外 or 0=指定しない)
define('ONLYM', "0");

//日付(1=ファイルの更新日時(推奨) 2=常に最新日時 0=指定しない)
define('DAY', "1");

//更新頻度(全フォルダ共通(always,hourlyなど) or 0=指定しない)
define('CHA', "0");

//優先度(全フォルダ共通 1=指定する 0=指定しない)
define('PRI0', "1");

//ルートフォルダの優先度(0.0から1.0まで)
define('PRI', "1.0");

//サブフォルダ(最後の/なし)とその優先度(0.0から1.0まで)
$dirs = array(
"info" => "0.8",
"info/mail" => "0.5",
);

//---------------設定ここまで---------------

header('Content-Type:text/xml; charset=utf-8' );

$dir_path = getcwd();
$filename = array();
$all = 0;

//ルートフォルダ
$dir = dir($dir_path);
while($file = $dir->read()) {
	if(!ereg('(^\.$)|(^\.\.$)', $file)) {
		$filename[] = $file;
		rsort($filename); //降順
		if(SORT){
			sort($filename); //昇順
		}
	}
}
$dir->close();
for($i=0;$i<count($filename);$i++) {
	if(ereg(EXT,$filename[$i])){
		if(NONE){if(ereg(NONE,$filename[$i])){continue;}}
		if(ONLYL){if(!ereg(ONLYL,$filename[$i])){continue;}}
		if(DAY==2){$ltime = date("Y-m-d\TH:i:s+09:00");}
		if(DAY==1){$mtime = filemtime("$filename[$i]");
		$ltime = date("Y-m-d\TH:i:s+09:00" ,$mtime);}
		$filenamet.= '<url>
<loc>'.URL.''.$filename[$i].'</loc>';
		if(DAY){$filenamet.= '
<lastmod>'.$ltime.'</lastmod>';}
		if(CHA){$filenamet.= '
<changefreq>'.CHA.'</changefreq>';}
		if(PRI0){$filenamet.= '
<priority>'.PRI.'</priority>';}
		$filenamet.= '
</url>
';
	}
	if(ereg("index.",$filename[$i])){
		if(NONE){if(ereg(NONE,$filename[$i])){continue;}}
		if(ONLYL){if(!ereg(ONLYL,$filename[$i])){continue;}}
		if(DAY==2){$ltime = date("Y-m-d\TH:i:s+09:00");}
		if(DAY==1){$mtime = filemtime("$filename[$i]");
		$ltime = date("Y-m-d\TH:i:s+09:00" ,$mtime);}
		$filenamei.= '<url>
<loc>'.URL.'</loc>';
		if(DAY){$filenamei.= '
<lastmod>'.$ltime.'</lastmod>';}
		if(CHA){$filenamei.= '
<changefreq>'.CHA.'</changefreq>';}
		if(PRI0){$filenamei.= '
<priority>'.PRI.'</priority>';}
		$filenamei.= '
</url>
';
	}
}

//サブフォルダ
while(list ($key, $val) = each($dirs)){
	if (is_dir($key)) {
		if ($dh = opendir($key)) {
			$pri = "$val";
			while (($files = readdir($dh)) !== false) {
				$file[] = $files;
			}
			rsort($file); //降順
			if(SORT){
				sort($file); //昇順
			}
			$all = 0;
			for($i=0;$i<count($file);$i++) {
				if(ereg("index.",$file[$i])){
					if(NONE){if(ereg(NONE,$file[$i])){continue;}}
					if(ONLYM){if(!ereg(ONLYM,$file[$i])){continue;}}
					if(DAY==2){$ltime = date("Y-m-d\TH:i:s+09:00");}
					if(DAY==1){$mtime = filemtime("$key/$file[$i]");
					$ltime = date("Y-m-d\TH:i:s+09:00" ,$mtime);}
					$filenames.= '<url>
<loc>'.URL.''.$key.'/</loc>';
					if(DAY){$filenames.= '
<lastmod>'.$ltime.'</lastmod>';}
					if(CHA){$filenames.= '
<changefreq>'.CHA.'</changefreq>';}
					if(PRI0){$filenames.= '
<priority>'.$pri.'</priority>';}
					$filenames.= '
</url>
';
				}
				if(ereg(EXT,$file[$i])){
					if(NONE){if(ereg(NONE,$file[$i])){continue;}}
					if(ONLYM){if(!ereg(ONLYM,$file[$i])){continue;}}
					if(DAY==2){$ltime = date("Y-m-d\TH:i:s+09:00");}
					if(DAY==1){$mtime = filemtime("$key/$file[$i]");
					$ltime = date("Y-m-d\TH:i:s+09:00" ,$mtime);}
					$filenames.= '<url>
<loc>'.URL.''.$key.'/'.$file[$i].'</loc>';
					if(DAY){$filenames.= '
<lastmod>'.$ltime.'</lastmod>';}
					if(CHA){$filenames.= '
<changefreq>'.CHA.'</changefreq>';}
					if(PRI0){$filenames.= '
<priority>'.$pri.'</priority>';}
					$filenames.= '
</url>
';
				}
				$all++;
			}
			closedir($dh);
			$file = array();
		}
	}
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
'.$filenamei.''.$filenamet.''.$filenames.'</urlset>';

echo $xml;

?>
