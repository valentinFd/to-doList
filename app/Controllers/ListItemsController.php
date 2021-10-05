<?php

namespace App\Controllers;

use App\Models\ListItem;
use App\Storage\CSVStorage;

class ListItemsController
{
    public static function index(): void
    {
        $csv = new CSVStorage("storage/to-doList.csv");
        $listItems = $csv->getListItems();
        require_once("app/Views/index.template.php");
    }

    public static function create(string $text): void
    {
        $csv = new CSVStorage("storage/to-doList.csv");
        $csv->add(new ListItem($text));
        header("Location: /");
    }

    public static function delete(string $id): void
    {
        $csv = new CSVStorage("storage/to-doList.csv");
        $csv->delete($csv->searchByListItemId($id));
        header("Location: /");
    }
}
