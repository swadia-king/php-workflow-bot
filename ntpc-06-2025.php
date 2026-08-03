<?php

$url = "https://rrbahmedabad.gov.in/exam/cen-06-2025-non-technical-popular-categories-graduate/";

// Chrome User Agent
$userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => $userAgent,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 30,
]);

$html = curl_exec($ch);
curl_close($ch);

libxml_use_internal_errors(true);

$dom = new DOMDocument();
$dom->loadHTML($html);

$xpath = new DOMXPath($dom);

// Find all news-update-content divs
$nodes = $xpath->query("//div[contains(concat(' ', normalize-space(@class), ' '), ' news-update-content ')]");
$yesterday = (new DateTime('yesterday'))->format('d/m/Y');
foreach ($nodes as $node) {

    // Anchor
    $a = $xpath->query("./a", $node)->item(0);

    if (!$a) {
        continue;
    }

    $href = trim($a->getAttribute("href"));
    $title = trim($a->textContent);

    // First span immediately under this div
    $span = $xpath->query("./span[1]", $node)->item(0);
    $date = $span ? trim($span->textContent) : "";
    if ($date !== $yesterday) {
    	echo "today : $yesterday and post_date : $date\n";
        continue;
    }
    echo "Title : $title\n";
    echo "Href  : $href\n";
    echo "Date  : $date\n";
    echo "-------------------------\n";
}
