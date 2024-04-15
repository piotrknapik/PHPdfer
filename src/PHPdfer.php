<?php

namespace PHPdfer;

use Exception;

class PHPdfer
{
    /**
     * @param string $pdf - path to PDF file
     * @param array $arMetadata - array with metadata for PDF file
     * @param bool $logMode - enables the mode in which the output of the CLI command is saved to a log file
     *
     * @throws Exception - if it will not succeed change metadata in PDF file
     */
    public function changeMetadata(string $pdf, array $arMetadata, bool $logMode = false): void
    {
        $metaDataDirector = new MetadataDirector();

        try {
            $metaDataDirector->createMetadataFile($arMetadata);
        } catch (Exception $e) {
            echo $e;
        }

        preg_match('/[ \w-]+?(?=\.)/', $pdf, $matches);

        $newFileName = "phpdfer_{$matches[0]}.pdf";

        file_put_contents($newFileName, '');

        $commandResult = exec(
            "gs -dSAFER -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -sOutputFile=$newFileName $pdf tmp_meta_data.pdf",
            $arCommandOutput,
            $commandStatus
        );
        unlink('tmp_meta_data.pdf');

        if ($logMode) {
            file_put_contents('command_output.log', $arCommandOutput);
        }

        if (!$commandResult) {
            throw new Exception("Failed execute Ghost Script command, command status: $commandStatus");
        }
    }
}
