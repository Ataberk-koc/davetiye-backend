<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    /**
     * Tüm davetiyeleri listele
     */
    public function index(): JsonResponse
    {
        $invitations = Invitation::all()->map(function ($invitation) {
            return $this->formatInvitation($invitation);
        });
        
        return response()->json([
            'success' => true,
            'data' => $invitations,
        ]);
    }

    /**
     * Tek bir davetiyeyi getir
     */
    public function show($id): JsonResponse
    {
        $invitation = Invitation::find($id);
        
        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Davetiye bulunamadı',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $this->formatInvitation($invitation),
        ]);
    }

    /**
     * Yeni davetiye oluştur
     */
    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        $validated = $request->validate([
            'groom_name' => 'required|string|max:255',
            'groom_surname' => 'required|string|max:255',
            'bride_name' => 'required|string|max:255',
            'bride_surname' => 'required|string|max:255',
            'wedding_date' => 'required|date_format:Y-m-d H:i',
            'event_type' => 'required|in:düğün,nişan,kına,söz,diğer',
            'location' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $invitation = new Invitation();
        $invitation->groom_name = $validated['groom_name'];
        $invitation->groom_surname = $validated['groom_surname'];
        $invitation->bride_name = $validated['bride_name'];
        $invitation->bride_surname = $validated['bride_surname'];
        $invitation->wedding_date = $validated['wedding_date'];
        $invitation->event_type = $validated['event_type'];
        $invitation->location = $validated['location'] ?? null;
        $invitation->description = $validated['description'] ?? null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('invitations', 'public');
            $invitation->image = $path;
        }

        $invitation->save();

        return response()->json([
            'success' => true,
            'message' => 'Davetiye başarıyla oluşturuldu',
            'data' => $this->formatInvitation($invitation),
        ], 201);
    }

    /**
     * Davetiyeyi güncelle
     */
    public function update(\Illuminate\Http\Request $request, $id): JsonResponse
    {
        $invitation = Invitation::find($id);
        
        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Davetiye bulunamadı',
            ], 404);
        }

        $validated = $request->validate([
            'groom_name' => 'sometimes|string|max:255',
            'groom_surname' => 'sometimes|string|max:255',
            'bride_name' => 'sometimes|string|max:255',
            'bride_surname' => 'sometimes|string|max:255',
            'wedding_date' => 'sometimes|date_format:Y-m-d H:i',
            'event_type' => 'sometimes|in:düğün,nişan,kına,söz,diğer',
            'location' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $invitation->update($validated);

        if ($request->hasFile('image')) {
            if ($invitation->image) {
                \Storage::disk('public')->delete($invitation->image);
            }
            $path = $request->file('image')->store('invitations', 'public');
            $invitation->image = $path;
            $invitation->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Davetiye başarıyla güncellendi',
            'data' => $this->formatInvitation($invitation),
        ]);
    }

    /**
     * Davetiyeyi sil
     */
    public function destroy($id): JsonResponse
    {
        $invitation = Invitation::find($id);
        
        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => 'Davetiye bulunamadı',
            ], 404);
        }

        if ($invitation->image) {
            \Storage::disk('public')->delete($invitation->image);
        }

        $invitation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Davetiye başarıyla silindi',
        ]);
    }

    /**
     * Davetiyeyi format et (image URL'sini ekle)
     */
    private function formatInvitation($invitation)
    {
        $data = $invitation->toArray();
        if ($invitation->image) {
            $data['image_url'] = asset('storage/' . $invitation->image);
        }
        return $data;
    }
    // 1. ANILARI LİSTELEME (Frontend sayfa açılınca bunu çağıracak)
    public function getMoments($id)
    {
        $invitation = Invitation::find($id);

        if (!$invitation) {
            return response()->json(['message' => 'Davetiye bulunamadı'], 404);
        }

        // Sadece onaylı olanları, en yeniden eskiye doğru getir
        $moments = $invitation->moments()
            ->where('is_approved', true)
            ->latest()
            ->get()
            ->map(function ($moment) {
                return [
                    'id' => $moment->id,
                    // Frontend'de göstermek için tam URL oluşturuyoruz
                    'image_url' => asset('storage/' . $moment->image_path),
                    'caption' => $moment->caption,
                    'created_at' => $moment->created_at->diffForHumans(),
                ];
            });

        return response()->json(['data' => $moments]);
    }

    // 2. YENİ ANI YÜKLEME (Frontend "Gönder" diyince burası çalışacak)
    public function addMoments(Request $request, $id)
    {
        $invitation = Invitation::find($id);

        if (!$invitation) {
            return response()->json(['message' => 'Davetiye bulunamadı'], 404);
        }

        $request->validate([
            'photos' => 'required|array',
            'photos.*.image_data' => 'required|string', // Base64 string
            'photos.*.caption' => 'nullable|string|max:500',
        ]);

        try {
            $savedMoments = [];

            foreach ($request->photos as $photoData) {
                // Base64 verisinden resmi ayrıştır
                $image_64 = $photoData['image_data']; 
                
                // Base64 başlığını temizle (data:image/jpeg;base64, kısmı)
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   
                $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
                $image = str_replace($replace, '', $image_64); 
                $image = str_replace(' ', '+', $image); 
                
                // Benzersiz dosya ismi oluştur
                $imageName = 'moments/' . Str::random(10) . '.' . $extension;
                
                // Dosyayı storage/app/public/moments klasörüne kaydet
                Storage::disk('public')->put($imageName, base64_decode($image));

                // Veritabanına kaydet
                $moment = $invitation->moments()->create([
                    'image_path' => $imageName,
                    'caption' => $photoData['caption'] ?? null,
                ]);
                
                $savedMoments[] = $moment;
            }

            return response()->json([
                'message' => 'Fotoğraflar başarıyla yüklendi',
                'count' => count($savedMoments)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Yükleme sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
