<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Give dentists without a working photo the bundled portraits in public/images/dentists.
     */
    public function up(): void
    {
        $photos = [
            'Abdirahman' => 'images/dentists/dr-abdirahman.jpg',
            'Hodan' => 'images/dentists/dr-hodan.jpg',
        ];

        foreach ($photos as $name => $path) {
            $users = DB::table('users')
                ->join('dentists', 'dentists.user_id', '=', 'users.id')
                ->where('users.name', 'like', "%{$name}%")
                ->select('users.id', 'users.photo')
                ->get();

            foreach ($users as $user) {
                $missing = ! $user->photo
                    || (! str_starts_with($user->photo, 'images/') && ! Storage::disk('public')->exists($user->photo));

                if ($missing) {
                    DB::table('users')->where('id', $user->id)->update(['photo' => $path]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('users')->where('photo', 'like', 'images/dentists/%')->update(['photo' => null]);
    }
};
