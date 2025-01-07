<?php

namespace App\Http\Controllers;

use Throwable;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\throwException;
use Illuminate\Support\Facades\Log;


class Upload
{

    public static function store(UploadedFile $document, String $bucket, $folder = null, $disk = 'minio', $documentname = null)
    {
        $file = $document;
        $extension = $file->getClientOriginalExtension();

        $fileName = time() . '' . uniqid() . '.' . $extension;
        $filePath = $fileName;

        $minio = app('minio');

        try {
            $bucketExists = $minio->headBucket([
                'Bucket' => $bucket,
            ]);
        } catch (S3Exception $e) {
            if ($e->getStatusCode() === 404) {
                $minio->createBucket([
                    'Bucket' => $bucket,
                ]);
            } else {
                throw $e;
            }
        }

        $result = $minio->putObject([
            'Bucket' => $bucket,
            'Key'    => $filePath,
            'Body'   => fopen($file->getPathname(), 'r'),
            'ACL'    => 'private', // Access Control List: public-read or private
        ]);

        /*
        $customUrl =
            config('app.url') . '/api/uploads/' .
            $bucket . '/' .
            $filePath;
            */


        $customUrl = $filePath;

        return $customUrl;
    }

    public function download(String $bucket, String $filename)
    {
        $minio = app('minio');

        try {
            $result = $minio->getObject([
                'Bucket' => $bucket,
                'Key'    => $filename,
            ]);

            $contentType = $result['ContentType'];

            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                $contentType = 'image/' . $extension;
            } elseif (strtolower($extension) === 'pdf') {
                $contentType = 'application/pdf';
            } else {
                abort(415, 'Unsupported file type.');
            }

            return response($result['Body'])
                ->header('Content-Type', $contentType)
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Aws\S3\Exception\S3Exception $e) {
            if ($e->getStatusCode() === 404) {
                abort(404, 'File not found.');
            }

            Log::error('Error downloading file from MinIO', ['exception' => $e]);

            throw $e;
        }
    }


    // public static function getAbosoluteUrl(String $filename)
    // {
    //     return "https://lpa.softsolutionsdev.com/api/uploads/{$filename}";
    // }

    public static function getAbosoluteUrl(String $bucket, ?String $filename)
    {
        if (!$filename) {
            return '';  // Retourner une chaîne vide si le fichier est null
        }

        // Si l'URL doit être publique, on génère l'URL de manière simple
        $baseUrl = env('MINIO_ENDPOINT');
        $url = "{$baseUrl}/{$bucket}/{$filename}";

        // Si tu veux une URL pré-signée, tu peux utiliser ceci :
        // $minio = app('minio');
        // $url = $minio->getObjectUrl($bucket, $filename, '+10 minutes');  // URL pré-signée valable 10 minutes

        return $url;
    }




    public function deleteDocument($path, $disk = 'public')
    {
        Storage::disk($disk)->delete($path);
    }
}
