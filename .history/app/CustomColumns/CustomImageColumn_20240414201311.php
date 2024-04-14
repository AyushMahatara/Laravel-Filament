<?php

namespace App\CustomColumns;

use Filament\Tables\Columns\Column;

class CustomImageColumn extends Column
{
    public function __construct(string $label, ?string $attribute = null)
    {
        parent::__construct($label, $attribute);

        $this->view('filament.custom-image-column');
    }

    public function imageUrl($record): string
    {
        // Modify this method to return the URL of the image field from the record
        return $record->{$this->getAttribute()}->url();
    }
}
