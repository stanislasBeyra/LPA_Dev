<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
