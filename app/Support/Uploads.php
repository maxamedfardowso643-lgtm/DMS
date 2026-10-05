<?php

namespace App\Support;

use App\Models\StoredFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Uploaded images live on the public disk and are also copied into the
 * database, so they can be restored after a redeploy wipes the disk.
 */
class Uploads
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'public');

        StoredFile::updateOrCreate(['path' => $path], [
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'contents' => file_get_contents($file->getRealPath()),
        ]);

        return $path;
    }

    /**
     * Make sure the file is on the public disk, restoring it from the
     * database copy if needed. Returns false when neither has it.
     */
    public static function ensureOnDisk(string $path): bool
    {
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return true;
        }

        $stored = StoredFile::where('path', $path)->first();

        if (! $stored) {
            return false;
        }

        $disk->put($path, $stored->contents);

        return true;
    }
}
