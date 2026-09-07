<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TemplatesController extends Controller
{
    public function index()
    {
        $templatesPath = resource_path('views/templates');
        $files = [];

        if (File::exists($templatesPath)) {
            $allFiles = File::files($templatesPath);

            foreach ($allFiles as $file) {
                $filename = $file->getFilename();

                if (str_starts_with($filename, 'surat') && str_ends_with($filename, '.blade.php')) {
                    $files[] = [
                        'filename' => $filename,
                        'name' => str_replace(['_', '.blade.php', '_'], [' ', '', ' '], $filename),
                        'size' => $file->getSize(),
                        'modified' => $file->getMTime(),
                    ];
                }
            }

            usort($files, fn($a, $b) => strcmp($a['filename'], $b['filename']));
        }

        return view('templates.index', compact('files'));
    }

    public function download(Request $request, $filename)
    {
        $request->validate([
            'no_surat' => 'nullable|string',
        ]);

        $templatesPath = resource_path('views/templates');
        $filePath = $templatesPath . '/' . $filename;
        $filename = str_replace('.blade.php', '', $filename);

        if (!File::exists($filePath) || !str_starts_with($filename, 'surat')) {
            abort(404, 'Template tidak ditemukan');
        }

        // Cari Kepala Sekolah yang memiliki role kepala-sekolah
        $kepalaSekolah = User::whereHas('roles', function($query) {
                $query->whereIn('code', ['kepala-sekolah', 'kepala_sekolah'])
                      ->orWhere('name', 'like', '%Kepala Sekolah%');
            })
            ->whereHas('employee', function($query) {
                $query->whereNotNull('nip')
                      ->where('nip', '!=', '')
                      ->where('nip', '!=', '-');
            })
            ->with('employee')
            ->first();

        // Fallback: cari user dengan role kepala-sekolah apapun jika tidak ditemukan NIP khusus
        if (!$kepalaSekolah) {
            $kepalaSekolah = User::whereHas('roles', function($query) {
                    $query->whereIn('code', ['kepala-sekolah', 'kepala_sekolah'])
                          ->orWhere('name', 'like', '%Kepala Sekolah%');
                })
                ->with('employee')
                ->first();
        }

        // Fallback email/user jika belum ada role kepala-sekolah
        if (!$kepalaSekolah) {
            $kepalaSekolah = User::where('email', 'kepsek@gmail.com')
                ->with('employee')
                ->first();
        }

        if (!$kepalaSekolah) {
            $kepalaSekolah = User::with('employee')->first();
        }

        // Format data kepala sekolah & NIP agar terisi dengan baik
        $name = $kepalaSekolah?->employee?->full_name ?? ($kepalaSekolah?->name ?? '-');
        $nip = $kepalaSekolah?->employee?->nip ?? ($kepalaSekolah?->employee?->nuptk ?? '-');

        $kepsekData = (object)[
            'name' => $name,
            'nip' => $nip
        ];

        // Set juga property pada object $kepalaSekolah
        if ($kepalaSekolah) {
            $kepalaSekolah->name_formatted = $name;
            $kepalaSekolah->nip_formatted = $nip;
        }

        $no_surat = $request->input('no_surat');

        return view('templates.' . $filename, compact('kepalaSekolah', 'kepsekData', 'no_surat'));
    }
}
