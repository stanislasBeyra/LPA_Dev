<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;




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

            $user=Auth::user();
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

            $notification=new NotificationController();
            $notification->sendNotification($user->id,"message de teste de notification push");

            // Retourner une réponse avec succès
            return back()->with('success', 'Banner uploaded successfully.');
        } catch (\Exception $e) {
            Log::info('An ocured error' . $e->getMessage(),);
            return back()->with('error', 'Acured error' . $e->getMessage());
        }
    }


    public function getBannerforAdmin()
    {
        try {
            $banners = Banner::orderBy('id', 'desc')
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
                ->orderBy('id', 'asc')
                ->take(5)
                ->get();
            return response()->json(['success', true, 'banners' => $banners]);
        } catch (\Exception $e) {
            Log::info('An ocured error' . $e->getMessage(),);
            return back()->with('error', 'Acured error' . $e->getMessage());
        }
    }

    

    public function desactivebanner(Request $request)
    {
        try {
            // Trouver le banner avec l'ID passé en paramètre
            $banner = Banner::find($request->bannerId);

            $user=Auth::user();
            // Vérifier si le banner existe
            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found'
                ], 404);
            }

            // Mettre à jour le statut du banner 
            $newStatus = $request->is_active == 'on' ? 1 : 0;
            $banner->update([
                'is_active' => $newStatus
            ]);

            $notification=new NotificationController();
            $notification->sendNotification($user->id,"message de teste de notification push");
            // Retourner une réponse JSON de succès
            return response()->json([
                'success' => true,
                'message' => 'Banner status successfully updated',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            // Log the error and return an error JSON response
            Log::error('Error updating banner status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function DeleteBanner(Request $request)
    {
        try {
            $banner = Banner::find($request->bannerId);

            // Vérifier si le banner existe
            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner not found'
                ], 404);
            }
            $banner->delete();
            return response()->json([
                'success' => true,
                'message' => 'Banner delete successfully updated',
            ]);

        } catch (\Exception $e) {
            // Log the error and return an error JSON response
            Log::error('Error delete banner: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
