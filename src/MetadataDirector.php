<?php

namespace PHPdfer;

use Exception;

class MetadataDirector
{
    public function createMetadataFile(array $arMetadata)
    {
        $pdfMetaData = $this->getMetaData($arMetadata);
        $writeResult = file_put_contents('tmp_meta_data.pdf', $pdfMetaData);

        if (!$writeResult) {
            throw new Exception('Failed create temporary pdf file with new meta data');
        }
    }

    private function getMetaData(array $arMetadata)
    {
        $metaDataBuilder = new MetadataBuilder();
        $metaData = $metaDataBuilder->setFirstCharacter();
        $now = date('Ymdhis');

        if ($arMetadata['TITLE']) {
            $metaData .= $metaDataBuilder->setTitle($arMetadata['TITLE']);
        }

        if ($arMetadata['AUTHOR']) {
            $metaData .= $metaDataBuilder->setAuthor($arMetadata['AUTHOR']);
        }

        if ($arMetadata['SUBJECT']) {
            $metaData .= $metaDataBuilder->setSubject($arMetadata['SUBJECT']);
        }

        if ($arMetadata['KEYWORDS']) {
            $metaData .= $metaDataBuilder->setKeywords($arMetadata['KEYWORDS']);
        }

        if ($arMetadata['MOD_DATE']) {
            $metaData .= $metaDataBuilder->setModDate($arMetadata['MOD_DATE']);
        } else {
            $metaData .= $metaDataBuilder->setModDate($now);
        }

        if ($arMetadata['CREATION_DATE']) {
            $metaData .= $metaDataBuilder->setCreationDate($arMetadata['CREATION_DATE']);
        } else {
            $metaData .= $metaDataBuilder->setCreationDate($now);
        }

        if ($arMetadata['CREATOR']) {
            $metaData .= $metaDataBuilder->setCreator($arMetadata['CREATOR']);
        } else {
            $metaData .= $metaDataBuilder->setCreator();
        }

        $metaData .= $metaDataBuilder->setLastCharacter();

        return $metaData;
    }
}
