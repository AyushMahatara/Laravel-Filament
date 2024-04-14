@props(['record'])

<a href="{{ $record->certificate_image->url() }}" target="_blank">
    <img src="{{ $column->imageUrl($record) }}" style="max-width: 100px;">
</a>