<?php require_once(__DIR__ . '/../initial.php');

if (!isset($_GET['search'])) {
    http_response_code(400);
    exit;
}

$search = strtolower($_GET['search']);

$results = [];

$word = db_get_record('words', ['word' => $search]);
if ($word) {
    $word->reason = $search;
    $results[] = $word;
}

$results = array_merge($results, db_get_records_sql(
    'SELECT words.*, synonyms.synonym AS reason FROM words JOIN synonyms on words.id = synonyms.word
          WHERE synonym LIKE ?'
    , ["$search%"]));

$ids = array_map(function($r) { return $r->id; }, $results);
if (count($ids)) {
    $results = array_merge($results, db_get_records_sql(
        'SELECT words.*, synonyms.synonym AS reason FROM words JOIN synonyms on words.id = synonyms.word
          WHERE synonym LIKE ? AND id NOT IN ' . db_create_in_query($ids)
        , array_merge(["%$search%"], $ids)));
} else {
    $results = array_merge($results, db_get_records_sql(
        'SELECT words.*, synonyms.synonym AS reason FROM words JOIN synonyms on words.id = synonyms.word
          WHERE synonym LIKE ?'
        , ["%$search%"]));
}

foreach ($results as $result) {
    unset($result->id);
}

header('Content-Type: application/json');
echo json_encode($results);
