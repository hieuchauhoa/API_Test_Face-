<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FaceComparisonController extends Controller
{
    public function compareFaces(Request $request)
    {
        $api_key = "0Wof52Pbm-QI-FOfvuJJ8B0sh3OTMbtX";
        $api_secret = "qmQCJBdD1iVGZ5iM5TVq4aF87TlVXXnT";

        $img1 = $request->input('img1');
        $img2 = $request->input('img2');

        $client = new Client(['base_uri' => 'https://api-us.faceplusplus.com/']);

        $response1 = $client->request('POST', 'facepp/v3/detect', [
            'multipart' => [
                [
                    'name' => 'api_key',
                    'contents' => $api_key
                ],
                [
                    'name' => 'api_secret',
                    'contents' => $api_secret
                ],
                [
                    'name' => 'image_url',
                    'contents' => $img1
                ]
            ]
        ]);

        $response2 = $client->request('POST', 'facepp/v3/detect', [
            'multipart' => [
                [
                    'name' => 'api_key',
                    'contents' => $api_key
                ],
                [
                    'name' => 'api_secret',
                    'contents' => $api_secret
                ],
                [
                    'name' => 'image_url',
                    'contents' => $img2
                ]
            ]
        ]);

        $face1 = json_decode($response1->getBody(), true)['faces'][0]['face_token'];
        $face2 = json_decode($response2->getBody(), true)['faces'][0]['face_token'];

        $response = $client->request('POST', 'facepp/v3/compare', [
            'multipart' => [
                [
                    'name' => 'api_key',
                    'contents' => $api_key
                ],
                [
                    'name' => 'api_secret',
                    'contents' => $api_secret
                ],
                [
                    'name' => 'face_token1',
                    'contents' => $face1
                ],
                [
                    'name' => 'face_token2',
                    'contents' => $face2
                ]
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        if ($result['confidence'] >= 70) {
            return response()->json(['result' => 1]);
        } else {
            return response()->json(['result' => 0]);
        }
    }
}
