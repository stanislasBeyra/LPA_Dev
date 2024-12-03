<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image; 

class BannerController extends Controller
{
    //

    public function storeBanner(Request $request)
    {
        // Validation des champs
        try {
            $request->validate([
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imagePath = null;

            // Chemin du répertoire de stockage des bannières
            $directory = public_path('app/public/banners');

            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $imageName = 'Banber' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $imageName);
                $imagePath = 'banners/' . $imageName;
            }
            Banner::create([
                'image_url' => $imagePath,
            ]);

            // Retourner une réponse avec succès
            return back()->with('success', 'Banner uploaded successfully.');
        } catch (\Exception $e) {
            Log::info('An ocured error' . $e->getMessage(),);
            return back()->with('error', 'Acured error' . $e->getMessage());
        }
    }

//     public function storeBanner(Request $request)
// {
//     try {
//         // Validation des champs
//         $request->validate([
//             'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//         ]);

//         $imagePath = null;

//         // Chemin du répertoire de stockage des bannières dans public
//         $directory = public_path('banners'); // Utilisation directe du répertoire public

//         // Vérifier et créer le dossier si nécessaire
//         if (!file_exists($directory)) {
//             mkdir($directory, 0755, true); // Crée le dossier si inexistant
//         }

//         // Vérifier si une image a été téléchargée
//         if ($request->hasFile('banner_image')) {
//             $image = $request->file('banner_image');
//             $imageName = 'Banner_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

//             // Redimensionner l'image à la taille souhaitée (1600x480)
//             $resizedImage = Image::make($image)
//                 ->resize(1600, 480, function ($constraint) {
//                     $constraint->aspectRatio(); // Maintient le ratio d'aspect
//                     $constraint->upsize();      // Empêche l'agrandissement de l'image si elle est plus petite
//                 });

//             // Enregistrer l'image redimensionnée dans le répertoire public
//             $resizedImage->save($directory . '/' . $imageName);

//             // Chemin relatif à enregistrer dans la base de données
//             $imagePath = 'banners/' . $imageName; // Chemin relatif pour la base de données
//         }

//         // Créer une nouvelle entrée dans la base de données
//         Banner::create([
//             'image_url' => $imagePath,
//         ]);

//         // Retourner une réponse avec succès
//         return back()->with('success', 'Banner uploaded and resized successfully.');
//     } catch (\Exception $e) {
//         // Log de l'erreur pour débogage
//         Log::error('An error occurred: ' . $e->getMessage());

//         return back()->with('error', 'An error occurred: ' . $e->getMessage());
//     }
// }

    public function getBannerforAdmin()
    {
        try {
            $banners = Banner::where('is_active', 1)
            ->orderBy('id','desc')
            ->get();
            return $banners;
        } catch (\Exception $e) {
            Log::info('An ocured error' . $e->getMessage(),);
            return back()->with('error', 'Acured error' . $e->getMessage());
        }
    }

    public function getBannerforEmployee()
    {
        try {
            $banners = Banner::where('is_active', 1)
            ->orderBy('id','desc')
            ->take(5)   
            ->get();
            return response()->json(['success',true,'banners'=>$banners]);
        } catch (\Exception $e) {
            Log::info('An ocured error' . $e->getMessage(),);
            return back()->with('error', 'Acured error' . $e->getMessage());
        }
    }
}
