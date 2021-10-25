<?php
function putColumnNames($params)
{
    list(
        'sheetObj' => $sheet, 
        'data' => $data, 
        'text_props' => $text_props,
        'field_props' => $field_props
    ) = $params;

    list(
        'is_text_centered_horizontal' => $is_text_centered_horizontal, 
        'is_text_centered_vertically' => $is_text_centered_vertically, 
        'is_bold' => $is_bold, 
        'font_size' => $font_size
        ) = $text_props;
    
    list (
        'width' => $field_width,
        'mergeColumns' => $mergeColumns,
        'mergeRows' => $mergeRows
    ) = $field_props;
    
    $total_cells_count = count($data) - 1;

    //if we had marge of columns or rows, recalculate total count of the cells.
    //Doing this because alignment, font-size and font-style go get all cells which are 
    //changed when there is merge
    if($mergeColumns > 0)
    {
        $total_cells_count *= ($mergeColumns + 1);
    }
    if($mergeRows > 0)
    {
        $total_cells_count *= ($mergeRows + 1);
    }
    $from_and_to_letters = getLettersIntervalByNStr(['start_letter' => 'A', 'count' => $total_cells_count]);
    $sheet->getStyle($from_and_to_letters)->getFont()->setSize($font_size);
    if ($is_text_centered_horizontal) {
        $sheet->getStyle($from_and_to_letters)->getAlignment()->setHorizontal('center');
    }
    if ($is_text_centered_vertically) {
        $sheet->getStyle($from_and_to_letters)->getAlignment()->setVertical('center');
    }
    if ($is_bold) {
        $sheet->getStyle($from_and_to_letters)->getFont()->setBold(true);
    }

    $data_keys = array_keys($data);
    for ($i = 1, $l = 'A'; $i <= count($data_keys) - 1; $i++, $l++) {
        $letter = $l . '1';
        if($mergeColumns > 0)
        {
            $end_range_letter = $l . ($mergeColumns + 1);
            $range = $letter . ':' . $end_range_letter;
            $sheet->mergeCells($range);
        }

        if($mergeRows > 0){
            $end_range_letter = ++$l . ($mergeRows + 1);
            $range = $letter . ':' . $end_range_letter;
            $sheet->mergeCells($range);
        }
        $sheet->getColumnDimension($l)->setWidth($field_width);
        $sheet->setCellValue($letter, $data_keys[$i]);
    }
}

function getLettersIntervalByNStr($params)
{
    list('start_letter' => $start_letter, 'count' => $count) = $params;
    $letters = [$start_letter];
    for ($i = 1, $current_letter = $letters[0]; $i <= $count - 1; $i++) {
        $letters[$i] = ++$current_letter;
    }
    $result = $letters[0] . ':' . $letters[count($letters) - 1];
    return $result;
}

function putContent($params)
{
    list(
        'sheetObj' => $sheet, 
        'data' => $data, 
        'text_props' => $text_props,
        'field_props' => $field_props,
        'heading_rows_count' => $heading_rows_count
    ) = $params;

    //heading_rows_count - this is the count of the heading columns. Usualy is 1 column, 
    //but if there some column merge , the count must be different than 1. This value is
    //used to know from which row and column to start put data.

    list(
        'is_text_centered_horizontal' => $is_text_centered_horizontal, 
        'is_text_centered_vertically' => $is_text_centered_vertically, 
        'is_bold' => $is_bold, 
        'font_size' => $font_size
        ) = $text_props;
    
    list (
        'width' => $field_width,
        'mergeColumns' => $mergeColumns,
        'mergeRows' => $mergeRows
    ) = $field_props;
    
    $total_cells_count = count($data) - 1;

    //if we had marge of columns or rows, recalculate total count of the cells.
    //Doing this because alignment, font-size and font-style go get all cells which are 
    //changed when there is merge
    if($mergeColumns > 0)
    {
        $total_cells_count *= ($mergeColumns + 1);
    }
    if($mergeRows > 0)
    {
        $total_cells_count *= ($mergeRows + 1);
    }
    $from_and_to_letters = getLettersIntervalByNStr(['start_letter' => 'A', 'count' => $total_cells_count]);
    $sheet->getStyle($from_and_to_letters)->getFont()->setSize($font_size);
    if ($is_text_centered_horizontal) {
        $sheet->getStyle($from_and_to_letters)->getAlignment()->setHorizontal('center');
    }
    if ($is_text_centered_vertically) {
        $sheet->getStyle($from_and_to_letters)->getAlignment()->setVertical('center');
    }
    if ($is_bold) {
        $sheet->getStyle($from_and_to_letters)->getFont()->setBold(true);
    }

        $data_keys = array_keys($data);

        for ($i = 1, $l = 'A'; $i <= count($data_keys) - 1; $i++, $l++) {
            $letter = $l . '' . ($heading_rows_count + 1);
            if($mergeColumns > 0)
            {
                $end_range_letter = $l . ($mergeColumns + $heading_rows_count);
                $range = $letter . ':' . $end_range_letter;
                $sheet->mergeCells($range);
            }

            if($mergeRows > 0)
            {
                $end_range_letter = ++$l . ($mergeRows + $heading_rows_count);
                $range = $letter . ':' . $end_range_letter;
                $sheet->mergeCells($range);
            }
            $sheet->getColumnDimension($l)->setWidth($field_width);
            $sheet->setCellValue($letter, $data[$data_keys[$i]]);
        }
}
