<?php

namespace App\Storage;

use App\Models\ListItem;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class CSVStorage
{
    private string $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function add(ListItem $listItem): void
    {
        $writer = Writer::createFromPath($this->fileName, "a");
        $writer->setDelimiter(";");
        $writer->insertOne((array)$listItem);
    }

    public function searchByListItemId(string $id): ?ListItem
    {
        $reader = Reader::createFromPath($this->fileName, "r");
        $reader->setDelimiter(";");
        $records = Statement::create()->process($reader);
        foreach ($records as $recordId => $record)
        {
            if ($recordId == $id) return new ListItem($record[0]);
        }
        return null;
    }

    public function delete(ListItem $listItem): void
    {
        if (($fileRead = fopen($this->fileName, "r")) !== false)
        {
            $listItems = [];
            while (($row = fgetcsv($fileRead, 1000, ";")) !== false)
            {
                if ($row[0] !== $listItem->getText())
                {
                    $listItems[] = $row;
                }
            }
            $fileWrite = fopen($this->fileName, "w");
            foreach ($listItems as $listItem)
            {
                fputcsv($fileWrite, $listItem, ";");
            }
            fclose($fileWrite);
            fclose($fileRead);
        }
    }

    public function getListItems(): array
    {
        $reader = Reader::createFromPath($this->fileName, "r");
        $reader->setDelimiter(";");
        $records = Statement::create()->process($reader);
        $listItems = [];
        foreach ($records as $record)
        {
            $listItems[] = new ListItem($record[0]);
        }
        return $listItems;
    }
}
