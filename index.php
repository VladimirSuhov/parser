<?php
include_once 'settings.php';
include_once 'lib/SQL.php';
include_once 'lib/curl_query.php';
include_once 'lib/simple_html_dom.php';


$sql = SQL::instance();

$html = curl_get('https://ntschool.ru/kursyi');

$dom =  str_get_html($html);

$courses = $dom->find('.courses-list--item-body');

foreach ($courses as $course) {
    $todb = ['id_school' => 1];

    $a = $course->find('a', 0);
    $todb['name'] = $a->plaintext;

    $one = curl_get('https://ntschool.ru/' . $a->href);
    $one_dom =  str_get_html($one);

    $price = $one_dom->find('.course1-ticket1--box-newPrice', 0);
    $todb['cost'] = $price->plaintext;

//    $sql->insert('courses', $todb);

    echo $price->plaintext;
    echo '<pre>';

}