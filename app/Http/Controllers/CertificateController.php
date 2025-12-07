<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('verifycertificate.verify', [
            'user' => $user,
            'certificate_verified' => $user->certificate_verified,
            'certificate_path' => $user->certificate_path,
            'verification_details' => $request->session()->get('certificate_verification_details'),
        ]);
    }


    public function upload(Request $request)
    {
        $user = $request->user();

        if (!$user->certificate_path || !\Storage::disk('public')->exists($user->certificate_path)) {
            return back()->withErrors(['certificate' => 'No certificate uploaded.']);
        }

        $fullpath = storage_path('app/public/' . $user->certificate_path);

        try {
            $text = $this->extractTextFromFile($fullpath);
        } catch (\Throwable $e) {
            return back()->withErrors(['certificate' => 'Cannot read certificate file.']);
        }

        $fields = [
            'pet_name' => $user->pet_name,
            'breedtype' => $user->pet_breed,
            'dog_age' => $user->pet_age,
            'gendertype' => $user->pet_gender,
        ];

        $match = $this->matchFieldsToText($fields, $text);

        if ($match['passed']) {
            $user->certificate_verified = true;
            $user->save();

            return redirect()->route('home')->with('success', 'Certificate verified successfully!');
        }

        return back()->withErrors(['certificate' => 'Verification failed: ' . $match['summary']]);
    }

    protected function extractTextFromFile(string $fullpath): string
    {
        $ext = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));

        if ($ext === 'pdf') {
            $cmd = 'pdftotext ' . escapeshellarg($fullpath) . ' -';
            exec($cmd, $output, $ret);
            if ($ret === 0) {
                return implode("\n", $output);
            }
            throw new \RuntimeException('pdftotext failed');
        } else {
            $cmd = 'tesseract ' . escapeshellarg($fullpath) . ' stdout';
            exec($cmd, $output, $ret);
            if ($ret === 0) {
                return implode("\n", $output);
            }
            throw new \RuntimeException('tesseract failed');
        }
    }

    protected function matchFieldsToText(array $fields, string $text): array
    {
        $textLower = mb_strtolower($text);
        $details = [];
        $passedCount = 0;
        $needed = count($fields);

        foreach ($fields as $k => $v) {
            $vClean = trim(mb_strtolower($v));
            if ($vClean === '') {
                $details[$k] = ['ok' => false, 'reason' => 'empty input'];
                continue;
            }

            if (mb_stripos($textLower, $vClean) !== false) {
                $details[$k] = ['ok' => true, 'matched' => $vClean];
                $passedCount++;
            } else {
                $onlyText = preg_replace('/[^a-z0-9]+/u', ' ', $textLower);
                $onlyVal = preg_replace('/[^a-z0-9]+/u', ' ', $vClean);
                if ($onlyVal && mb_stripos($onlyText, $onlyVal) !== false) {
                    $details[$k] = ['ok' => true, 'matched' => $vClean, 'method' => 'loose'];
                    $passedCount++;
                } else {
                    $details[$k] = ['ok' => false, 'reason' => 'no match', 'value' => $vClean];
                }
            }
        }

        $passed = ($passedCount >= max(1, ceil($needed * 0.75)));
        $summary = "Matched {$passedCount} of {$needed} fields.";

        return ['passed' => $passed, 'details' => $details, 'summary' => $summary];
    }
}
