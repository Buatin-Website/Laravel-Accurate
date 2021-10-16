<?php

namespace Buatin\Accurate;

use Buatin\Accurate\Models\AccurateSetting;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;

class Accurate
{
    public const ACCURATE_AUTH_ENDPONT = "https://account.accurate.id/oauth";
    public const ACCURATE_API_ENDPOINT = "https://zeus.accurate.id/accurate/api";

    public static function ACCURATE_API_ENDPOINT()
    {
        return env('ACCURATE_API_ENDPOINT', null);
    }

    public static function setting($key)
    {
        return AccurateSetting::find($key)->value ?? null;
    }

    /**
     * @throws Exception
     */
    public static function request($endpoint, $type, $query = null, $session = false): JsonResponse
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::setting('access_token'),
            ];
            if ($session) {
                $headers['X-Session-ID'] = self::setting('session');
            }

            $client = new Client([
                'headers' => $headers,
                'verify' => false,
            ]);

            $request = null;
            if (isset($query)) {
                if ($type == 'GET') {
                    $request = $client->get(
                        $endpoint,
                        [
                            'query' => $query,
                        ]
                    );
                } elseif ($type == 'POST') {
                    $request = $client->post(
                        $endpoint,
                        [
                            'form_params' => $query,
                        ]
                    );
                }
            } else {
                if ($type == 'GET') {
                    $request = $client->get(
                        $endpoint
                    );
                } elseif ($type == 'POST') {
                    $request = $client->post(
                        $endpoint
                    );
                }
            }

            if ($request->getStatusCode() == 200) {
                return response()->json(json_decode($request->getBody()->getContents()));
            } else {
                throw new Exception();
            }
        } catch (RequestException $th) {
            if ($th->hasResponse()) {
                $err = $th->getResponse();
                $response = $err->getReasonPhrase();
            } else {
                $response = $th->getMessage();
            }
            return response()->json([
                'message' => $response,
            ], $th->getCode());
        }
    }
}
