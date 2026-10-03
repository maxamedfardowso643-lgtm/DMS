<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Give Dr. Khalid the bundled portrait when he has no working photo.
     */
    public function up(): void
    {
        $users = DB::table('users')
            ->join('dentists', 'dentists.user_id', '=', 'users.id')
            ->where('users.name', 'like', '%Khalid%')
            ->select('users.id', 'users.photo')
            ->get();

        foreach ($users as $user) {
            $missing = ! $user->photo
                || (! str_starts_with($user->photo, 'images/') && ! Storage::disk('public')->exists($user->photo));

            if ($missing) {
                DB::table('users')->where('id', $user->id)->update(['photo' => 'images/dentists/dr-khalid.jpg']);
            }
        }
    }

    public function down(): void
    {
        DB::table('users')->where('photo', 'images/dentists/dr-khalid.jpg')->update(['photo' => null]);
    }
};
