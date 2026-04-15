<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Media files upload to DB, encode to base64 format, decode on retrieval from DB
// Incomplete feature

class MediaUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'images' => ['required_without:image', 'array', 'min:1'],
            'images.*' => ['string'],
            'image' => ['required_without:images'],
        ]);

        $images = $request->input('images', $request->input('image'));
        if (!is_array($images)) {
            $images = [$images];
        }

        $gameId = (int) $request->input('game_id');
        $rows = [];
        $coverUrl = null;
        $now = now();

        foreach ($images as $index => $imageData) {
            $stored = $this->storeBase64Image((string) $imageData);

            if ($index === 0) {
                $coverUrl = $stored['url'];
            }

            $rows[] = [
                'game_id' => $gameId,
                'type' => 'image',
                'url' => $stored['url'],
                'thumbnail_url' => $index === 0 ? null : $coverUrl,
                'sort_order' => $index + 1,
                'is_cover' => $index === 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::transaction(function () use ($gameId, $rows) {
            DB::table('game_media')
                ->where('game_id', $gameId)
                ->update(['is_cover' => false]);

            DB::table('game_media')->insert($rows);
        });

        return response()->json([
            'message' => 'Images uploaded successfully.',
            'inserted' => count($rows),
            'cover_url' => $coverUrl,
        ], 201);
    }

    public function downloadImage($id)
    {
        $url = DB::table('game_media')->where('id', $id)->value('url');

        if (!$url) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $path = parse_url($url, PHP_URL_PATH);
        $relativePath = ltrim((string) $path, '/');
        $filePath = public_path($relativePath);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath);
    }

    private function storeBase64Image(string $imageData): array
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
            abort(422, 'Invalid image format. Expected base64 data URI.');
        }

        $extension = strtolower($matches[1]);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed, true)) {
            abort(422, 'Unsupported image type.');
        }

        $base64Payload = substr($imageData, strpos($imageData, ',') + 1);
        $decodedImage = base64_decode($base64Payload, true);

        if ($decodedImage === false) {
            abort(422, 'Image decode failed.');
        }

        $uploadDir = public_path('uploads/game-media');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = (string) Str::uuid() . '.' . $extension;
        $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($fullPath, $decodedImage);

        return [
            'url' => asset('uploads/game-media/' . $fileName),
        ];
    }
}