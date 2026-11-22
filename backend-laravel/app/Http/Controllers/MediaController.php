<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\UserResource;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Media::query();

        if (request()->filled('search')) {
            $query->where('title', 'like', '%' . request('search') . '%');
        }

        $media = $query->get();

        return response()->json([
            'message' => 'Daftar media berhasil ditampilkan.',
            'data' => MediaResource::collection($media)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaRequest $request)
    {
        $media = Media::create($request->validated());

        $media->load('users');

        return response()->json([
            'message' => 'Media berhasil ditambahkan.',
            'data' => new MediaResource($media)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $media = Media::with('users')->find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail media berhasil ditampilkan.',
            'data' => new MediaResource($media)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMediaRequest $request, string $id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media tidak ditemukan.'
            ], 404);
        }

        $media->update($request->validated());

        $media->load('users');

        return response()->json([
            'message' => 'Media berhasil diperbarui.',
            'data' => new MediaResource($media)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $media = Media::find($id);

        if (!$media) {
            return response()->json([
                'message' => 'Media tidak ditemukan.'
            ], 404);
        }

        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus.'
        ], 200);
    }

    /**
     * Add media to user's collection.
     */
    public function addToCollection(string $user, string $medium)
    {
        $userData = User::find($user);
        $media = Media::find($medium);

        if (!$userData || !$media) {
            return response()->json([
                'message' => 'User atau media tidak ditemukan.'
            ], 404);
        }

        if ($userData->media()->where('media.id', $medium)->exists()) {
            return response()->json([
                'message' => 'Media sudah ada dalam koleksi pengguna.'
            ], 422);
        }

        $userData->media()->attach($medium);

        return response()->json([
            'message' => 'Media berhasil ditambahkan ke koleksi pengguna.'
        ], 200);
    }

    /**
     * Remove media from user's collection.
     */
    public function removeFromCollection(string $user, string $medium)
    {
        $userData = User::find($user);
        $media = Media::find($medium);

        if (!$userData || !$media) {
            return response()->json([
                'message' => 'User atau media tidak ditemukan.'
            ], 404);
        }

        $removed = $userData->media()->detach($medium);

        if (!$removed) {
            return response()->json([
                'message' => 'Media tidak ditemukan dalam koleksi pengguna.'
            ], 404);
        }

        return response()->json([
            'message' => 'Media berhasil dihapus dari koleksi pengguna.'
        ], 200);
    }

    /**
     * Display user's media collection.
     */
    public function userCollection(string $user)
    {
        $userData = User::with('media')->find($user);

        if (!$userData) {
            return response()->json([
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Koleksi media pengguna berhasil ditampilkan.',
            'data' => new UserResource($userData)
        ], 200);
    }
}
