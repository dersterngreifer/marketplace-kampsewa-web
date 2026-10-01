<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
{
    public function follow($user_id)
    {
        try {
            $current_user = auth('sanctum')->user();
            
            if (!$current_user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            if ($current_user->id == $user_id) {
                return response()->json(['message' => 'Tidak bisa follow diri sendiri'], 400);
            }

            $target_user = User::find($user_id);
            if (!$target_user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $exists = DB::table('followers')
                ->where('follower_id', $current_user->id)
                ->where('followed_id', $user_id)
                ->exists();

            if (!$exists) {
                DB::table('followers')->insert([
                    'follower_id' => $current_user->id,
                    'followed_id' => $user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return response()->json(['message' => 'Berhasil follow user'], 200);
            }

            return response()->json(['message' => 'Sudah mem-follow user ini'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unfollow($user_id)
    {
        try {
            $current_user = auth('sanctum')->user();
            
            if (!$current_user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $deleted = DB::table('followers')
                ->where('follower_id', $current_user->id)
                ->where('followed_id', $user_id)
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Berhasil unfollow user'], 200);
            }

            return response()->json(['message' => 'Belum mem-follow user ini'], 400);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function followers($user_id)
    {
        try {
            $user = User::find($user_id);
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $current_user_id = auth('sanctum')->id();

            $followers = $user->followers()->select('users.id', 'users.name', 'users.name_store', 'users.foto', 'users.foto_toko')->get();
            
            $followers->transform(function ($item) use ($current_user_id) {
                $item->is_followed_by_me = $current_user_id ? DB::table('followers')
                    ->where('follower_id', $current_user_id)
                    ->where('followed_id', $item->id)
                    ->exists() : false;
                
                $is_toko = !empty($item->name_store);
                $avatarUrl = null;

                if ($is_toko && $item->foto_toko && $item->foto_toko !== 'Belum Di isi') {
                    $avatarUrl = asset('assets/image/customers/logo_toko/' . $item->foto_toko);
                } elseif ($item->foto && $item->foto !== 'Belum Di isi') {
                    $avatarUrl = asset('assets/image/customers/profile/' . $item->foto);
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'avatar' => $avatarUrl,
                    'is_followed_by_me' => $item->is_followed_by_me
                ];
            });

            return response()->json([
                'message' => 'success',
                'data' => $followers
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function following($user_id)
    {
        try {
            $user = User::find($user_id);
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $current_user_id = auth('sanctum')->id();

            $following = $user->following()->select('users.id', 'users.name', 'users.name_store', 'users.foto', 'users.foto_toko')->get();
            
            $following->transform(function ($item) use ($current_user_id) {
                $item->is_followed_by_me = $current_user_id ? DB::table('followers')
                    ->where('follower_id', $current_user_id)
                    ->where('followed_id', $item->id)
                    ->exists() : false;
                
                $is_toko = !empty($item->name_store);
                $avatarUrl = null;

                if ($is_toko && $item->foto_toko && $item->foto_toko !== 'Belum Di isi') {
                    $avatarUrl = asset('assets/image/customers/logo_toko/' . $item->foto_toko);
                } elseif ($item->foto && $item->foto !== 'Belum Di isi') {
                    $avatarUrl = asset('assets/image/customers/profile/' . $item->foto);
                }

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'avatar' => $avatarUrl,
                    'is_followed_by_me' => $item->is_followed_by_me
                ];
            });

            return response()->json([
                'message' => 'success',
                'data' => $following
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
