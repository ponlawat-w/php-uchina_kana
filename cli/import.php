<?php
require_once(__DIR__ . '/../src/initial.php');

echo 'Truncating synonyms...';
db_delete_record('synonyms');
echo mysqli_query($CONNECTION, 'TRUNCATE TABLE synonyms') ? 'OK' . PHP_EOL : 'ERROR' . mysqli_error($CONNECTION);

echo 'Truncating words...';
db_delete_record('words');
echo mysqli_query($CONNECTION, 'SET FOREIGN_KEY_CHECKS = 0') ? 'OK' . PHP_EOL : 'ERROR' . mysqli_error($CONNECTION);
echo mysqli_query($CONNECTION, 'TRUNCATE TABLE words') ? 'OK' . PHP_EOL : 'ERROR' . mysqli_error($CONNECTION);
echo mysqli_query($CONNECTION, 'SET FOREIGN_KEY_CHECKS = 1') ? 'OK' . PHP_EOL : 'ERROR' . mysqli_error($CONNECTION);

$file = fopen(__DIR__ . '/data/database_yamatu→ucina.csv', 'r');
$head = fgetcsv($file);
$i = 1;
while ($row = fgetcsv_header($file, $head)) {
    $kanji = str_replace(['(', ')', '〔', '〕'], ['', '', '', ''], $row['kanji']);
    $wordObj = new stdClass();
    $wordObj->id = 0;
    $wordObj->word = $kanji ? $kanji : $row['word'];
    $wordObj->type = '';
    $wordObj->meaning = "（{$row['word']}）";
    $wordObj->meaning .= "{$row['meaning']}";
    if ($row['explanation']) {
        $wordObj->meaning .= "（{$row['explanation']}）";
    }

    $id = db_insert_record('words', $wordObj);
    if (!$id) {
        echo PHP_EOL;
        echo 'ERROR INSERT!: ';
        echo json_encode(['row' => $row, 'word' => $wordObj]) . PHP_EOL;
        echo mysqli_error($CONNECTION) . PHP_EOL;
        exit;
    }

    if ($kanji) {
        $synonym = new stdClass();
        $synonym->id = 0;
        $synonym->word = $id;
        $synonym->synonym = $kanji;
        if (!db_insert_record('synonyms', $synonym)) {
            echo PHP_EOL;
            echo 'ERROR INSERT!: ';
            echo json_encode(['synonym' => $synonym]) . PHP_EOL;
            echo mysqli_error($CONNECTION) . PHP_EOL;
            exit;
        }
    }

    if ($row['word']) {
        $synonym = new stdClass();
        $synonym->id = 0;
        $synonym->word = $id;
        $synonym->synonym = $row['word'];
        if (!db_insert_record('synonyms', $synonym)) {
            echo PHP_EOL;
            echo 'ERROR INSERT!: ';
            echo json_encode(['synonym' => $synonym]) . PHP_EOL;
            echo mysqli_error($CONNECTION) . PHP_EOL;
            exit;
        }
    }

    if ($i % 100 == 0) {
        echo "\r{$i} Inserted";
    }
    $i++;
}
echo PHP_EOL . 'Done!' . PHP_EOL;
fclose($file);

$file = fopen(__DIR__ . '/data/database_ucina→yamatu.csv', 'r');
$head = fgetcsv($file);
$i = 1;
while ($row = fgetcsv_header($file, $head)) {
    $words = explode('、', $row['word']);

    $wordObj = new stdClass();
    $wordObj->id = 0;
    $wordObj->word = $words[0];
    $wordObj->type = $row['type'];
    $wordObj->meaning = $row['accent'];
    $wordObj->meaning .= $row['literal_type'] ? '、' . $row['literal_type'] : '';
    $wordObj->meaning .= $row['etc'] ? '、' . $row['etc'] : '';
    $wordObj->meaning .= $row['meaning1'] ? '、' . $row['meaning1'] : '';
    $wordObj->meaning .= $row['meaning2'] ? '、' . $row['meaning2'] : '';
    $wordObj->meaning .= $row['meaning3'] ? '、' . $row['meaning3'] : '';
    $wordObj->meaning .= $row['meaning4'] ? '、' . $row['meaning4'] : '';
    $wordObj->meaning .= $row['meaning5'] ? '、' . $row['meaning5'] : '';
    $wordObj->meaning .= $row['remarks'] ? '、' . $row['remarks'] : '';

    $id = db_insert_record('words', $wordObj);
    if (!$id) {
        echo PHP_EOL;
        echo 'ERROR INSERT!: ';
        echo json_encode(['row' => $row, 'word' => $wordObj]) . PHP_EOL;
        echo mysqli_error($CONNECTION) . PHP_EOL;
        exit;
    }

    foreach ($words as $word) {
        if (!$word) {
            continue;
        }

        $synonym = new stdClass();
        $synonym->id = 0;
        $synonym->word = $id;
        $synonym->synonym = $word;
        if (!db_insert_record('synonyms', $synonym)) {
            echo PHP_EOL;
            echo 'ERROR INSERT!: ';
            echo json_encode(['synonym' => $synonym]) . PHP_EOL;
            echo mysqli_error($CONNECTION) . PHP_EOL;
            exit;
        }
        $synonym = new stdClass();
        $synonym->id = 0;
        $synonym->word = $id;
        $synonym->synonym = kana_from_romaji($word);
        if (!db_insert_record('synonyms', $synonym)) {
            echo PHP_EOL;
            echo 'ERROR INSERT!: ';
            echo json_encode(['synonym' => $synonym]) . PHP_EOL;
            echo mysqli_error($CONNECTION) . PHP_EOL;
            exit;
        }
    }

    if ($i % 100 == 0) {
        echo "\r{$i} Inserted";
    }
    $i++;
}
echo PHP_EOL . 'Done!' . PHP_EOL;
fclose($file);
