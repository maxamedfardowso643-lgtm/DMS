<?php

namespace App\Http\Controllers;

use App\Support\Uploads;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StoredFileController extends Controller
{
    /**
     * Serves /storage/* when the web server has no file there (no storage
     * link, or the disk was wiped by a redeploy).
     */
    public function show(string $path): BinaryFileResponse
    {
        abort_if(str_contains($path, '..'), 404);
        abort_unless(Uploads::ensureOnDisk($path), 404);

        return response()->file(Storage::disk('public')->path($path), [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
