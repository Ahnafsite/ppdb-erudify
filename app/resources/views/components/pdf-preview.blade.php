@if ($filePath && strpos($filePath, '.pdf') !== false)
    <iframe src="{{ $filePath }}" width="100%" height="600px" style="border:none;"></iframe>
@else
    <p>No PDF uploaded.</p>
@endif
