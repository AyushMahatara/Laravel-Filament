<?php

namespace App\CustomColumns;

use Filament\Tables\Columns\ImageColumn;

class CustomImageColumn extends ImageColumn
{
    protected $fullSizeUrlCallback;

    public function fullSizeUrl(callable $callback): self
    {
        $this->fullSizeUrlCallback = $callback;

        return $this;
    }

    public function getFullSizeUrl($record): string
    {
        if ($this->fullSizeUrlCallback && is_callable($this->fullSizeUrlCallback)) {
            return call_user_func($this->fullSizeUrlCallback, $record);
        }

        return '';
    }
}
