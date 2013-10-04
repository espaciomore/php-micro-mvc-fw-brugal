<?php
// run this script every 15 minutes
date_default_timezone_set("UTC");

$_ENV['ROOT']= dirname(__FILE__);

function __autoload($classname) {
    $parts= explode('\\',$classname);
    $filename= $_ENV['ROOT'].'/'. implode('/', $parts) .'.php';
    if ($parts[1]{0}==='I'){ 
      require(strtolower( $filename));     // interface
    } else { 
      require_once(strtolower( $filename));  // class
    }
}
spl_autoload_register('__autoload');

\Configs\Local::setup();

$db= new \Helpers\MySQL( array(
  "hostname"=> $_ENV['MYSQL_HOST'],
  "username"=> $_ENV['MYSQL_ROOT'],
  "password"=> $_ENV['MYSQL_PASSWORD'],
  "database"=> $_ENV['MYSQL_DATABASE'],
));

$set_indexes= function( $data ){
  $array= array(
    '<?xml version="1.0" encoding="UTF-8"?>',
    '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
  );
  foreach($data as $s){
    $array[]= "\t<sitemap>";
    $array[]= "\t\t<loc>".$_ENV['PROTOCOL'].'://[server-name]'.$s['path'].'</loc>';
    $array[]= "\t\t<lastmod>".date('Y-m-d',time()).'</lastmod>';
    $array[]= "\t</sitemap>";
  }
  $array[]= "</sitemapindex>";
  return implode("\n",$array);  
};

$set_urls= function( $data ){
  $array= array(
    '<?xml version="1.0" encoding="UTF-8"?>',
    '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
  );
  foreach($data as $url){
    $array[]= "\t<url>";
    $array[]= "\t\t<loc>".$_ENV['PROTOCOL'].'://[server-name]/'.$url.'</loc>';
    $array[]= "\t\t<lastmod>".date('Y-m-d',time()).'</lastmod>';
    $array[]= "\t</url>";
  }
  $array[]= "</urlset>";
  return implode("\n",$array);
};

$create_file= function($path,$content){
  $f= fopen( $_ENV['ROOT'].$path,'w');
  fwrite( $f,$content );
  fclose($f);
};

$sitemaps= array();
$sitemaps[]= array(
  'path'=> '/sitemaps/[main].xml',
  'content'=> $set_urls( array('') ),
);

foreach( $sitemaps as $s ){
  $create_file( $s['path'],$s['content'] );
}

$s_content= $set_indexes( $sitemaps );
$create_file( '/sitemap.xml',$s_content );

//