<?php

function parse_csv_file($file_path, $file_encodings = ['cp1251','UTF-8'], $col_delimiter = ','){

    if (!file_exists($file_path))
        return false;

    $cont = trim(file_get_contents($file_path));

    $encoded_cont = mb_convert_encoding($cont, 'UTF-8', mb_detect_encoding($cont, $file_encodings));

    unset($cont);

    $row_delimiter = "\r\n";
    if (false === strpos($encoded_cont, "\r\n"))
        $row_delimiter = "\n";

    $lines = explode($row_delimiter, trim($encoded_cont));
    $lines = array_filter($lines);
    $lines = array_map('trim', $lines);

    $data = [];
    foreach ($lines as $key => $line) {
        $data[] = str_getcsv( $line, $col_delimiter );
        unset($lines[$key]);
    }

    return $data;
}

function generateCsv($filepath, $data, $delimiter = ',', $enclosure = '"') {
    $handle = fopen($filepath, 'w+');
    fputcsv($handle, $data[0], $delimiter, $enclosure);
    unset($data[0]);
    foreach ($data as $line) {
        if ($line[6] != '')
            $line[6] = preg_replace('![^0-9]+!', '', $line[6]);
        if ($line[8] != '')
            $line[8] = date('m.d.y', strtotime($line[8]));

        fputcsv($handle, $line, $delimiter, $enclosure);
    }
    rewind($handle);
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);
    return $contents;
}

$data = parse_csv_file('data.csv');
generateCsv('file.csv', $data);
