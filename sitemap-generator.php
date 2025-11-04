<?php
$base_url = "https://acspromocodes.com/"; // 👉 এখানে তোর ডোমেইন লিখ (শেষে / দিতে ভুলবি না)
$root_dir = __DIR__;

function listFiles($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    foreach ($rii as $file) {
        if ($file->isDir()){ 
            continue;
        }
        if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'html') {
            $files[] = $file->getPathname();
        }
    }
    return $files;
}

$files = listFiles($root_dir);
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

foreach ($files as $file) {
    $relative = str_replace($root_dir . "/", "", $file);
    $url = $base_url . str_replace("\\", "/", $relative);
    $xml .= "  <url>" . PHP_EOL;
    $xml .= "    <loc>$url</loc>" . PHP_EOL;
    $xml .= "    <lastmod>" . date("Y-m-d", filemtime($file)) . "</lastmod>" . PHP_EOL;
    $xml .= "    <changefreq>weekly</changefreq>" . PHP_EOL;
    $xml .= "    <priority>0.8</priority>" . PHP_EOL;
    $xml .= "  </url>" . PHP_EOL;
}

$xml .= '</urlset>';

file_put_contents("sitemap.xml", $xml);

// ✅ গুগলকে পিং করা
$pingUrl = "https://www.google.com/ping?sitemap=" . urlencode($base_url . "sitemap.xml");
$response = @file_get_contents($pingUrl);

echo "✅ sitemap.xml তৈরি হয়েছে এবং গুগলকে জানিয়ে দেয়া হয়েছে!";