<?php

namespace App\Http\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use App\Repositories\Setting\SettingRepository;
use App\Repositories\Setting\SettingRepositoryInterface;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function __construct(private SettingRepositoryInterface $setting_repository) {}

    // GET /api/settings
    public function getGeneralSettings()
    {
        return response()->json([
            'success' => true,
            'data' => $this->setting_repository->generalSettingData()
        ]);
    }
    public function show($key)
    {
        $setting = $this->setting_repository->find($key);

        if (!$setting) {
            return response()->json([
                'success' => false,
                'message' => 'Setting not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $setting
        ]);
    }
}
