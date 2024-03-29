<?php

function appendLinksToSection($links, $section)
{
    //    $section->addLine();
    $section->addTextBreak();
    foreach ($links as $link) {
        //        $section->addLine();
        $section->addTextBreak();
        $section->addLink($link['url'], isset($link['title']) ? $link['title'] : $link['url'], array('color' => '0000FF'));
    }
}

function printDocSections($sections)
{
    foreach ($sections as $key => $value) {
        $sectionElement = $value->getElements();
        foreach ($sectionElement as $elementKey => $elementValue) {
            if ($elementValue instanceof \PhpOffice\PhpWord\Element\TextRun) {
                $secondSectionElement = $elementValue->getElements();
                foreach ($secondSectionElement as $secondSectionElementKey => $secondSectionElementValue) {
                    if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\Text) {
                        echo $secondSectionElementValue->getText();
                        echo "<br>";
                    }
                }
            }
        }
    }
}

//NOT PHPOFFICE FUNCTION , BUT WORKS!!!
function read_file_docx($filename)
{
    $striped_content = '';
    $content = '';
    if (!$filename || !file_exists($filename)) return false;
    $zip = zip_open($filename);
    if (!$zip || is_numeric($zip)) return false;
    while ($zip_entry = zip_read($zip)) {
        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
        if (zip_entry_name($zip_entry) != "word/document.xml") continue;
        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
        zip_entry_close($zip_entry);
    } // end while  
    zip_close($zip);
    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);
    return $striped_content;
}