<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\UserDetail;
use App\Domain\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements UserRepository
{
    const DB_USER_TABLE = 'users';

    public function findByCitizenship(string $city): Collection
    {
        return DB::table(self::DB_USER_TABLE)
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->join('countries', 'user_details.citizenship_country_id', '=', 'countries.id')
            ->where('users.active', '=', 1)
            ->where('countries.name', '=', $city)
            ->select('users.*', 'user_details.*')
            ->get();
    }

    public function delete(int $userId): bool
    {
        $user = DB::table(self::DB_USER_TABLE)->find($userId);
        if(!$user) {
            return false;
        }

        return DB::table(self::DB_USER_TABLE)->delete($user->id);
    }
}
