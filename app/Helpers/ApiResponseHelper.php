<?php

// If creating a new file, put this at the top of helpers file

use App\Enums\ResponseMethodEnum;

if (!function_exists('generalApiResponse')) {
    function generalApiResponse(
        ResponseMethodEnum $method,
        $resource = null,
        $dataPassed = null,
        $customMessage = null,
        $customStatusMsg = 'success',
        $customStatus = 200,
        $additionalData = null
    ) {
        return match ($method) {
            ResponseMethodEnum::SINGLE => !is_null($additionalData) ?
                $resource::make($dataPassed)
                ->additional(['status' => $customStatusMsg, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) :
                $resource::make($dataPassed)
                ->additional(['status' => $customStatusMsg, 'message' => $customMessage], $customStatus),

            ResponseMethodEnum::COLLECTION => !is_null($additionalData) ?
                $resource::collection($dataPassed)
                ->additional(['status' => $customStatusMsg, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) :
                $resource::collection($dataPassed)
                ->additional(['status' => $customStatusMsg, 'message' => $customMessage], $customStatus),

            ResponseMethodEnum::CUSTOM => !is_null($additionalData) ?
                response()->json(['status' => $customStatusMsg, 'data' => $dataPassed, 'message' => $customMessage, 'additional_data' => $additionalData], $customStatus) :
                response()->json(['status' => $customStatusMsg, 'data' => $dataPassed, 'message' => $customMessage], $customStatus),

            default => throw new InvalidArgumentException('Invalid response method'),
        };
    }
}
