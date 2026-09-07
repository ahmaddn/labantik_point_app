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

        // Cari Kepala Sekolah berdasarkan role, email, atau user pertama
        $kepalaSekolah = User::whereHas('roles', function($query) {
                $query->where('code', 'kepala-sekolah');
            })
            ->with('employee')
            ->first();

        if (!$kepalaSekolah) {
            $kepalaSekolah = User::where('email', 'kepsek@gmail.com')
                ->with('employee')
                ->first();
        }

        if (!$kepalaSekolah) {
            $kepalaSekolah = User::with('employee')->first();
        }

        // Format data kepala sekolah & NIP agar terisi dengan baik
        $kepsekData = (object)[
            'name' => $kepalaSekolah?->employee?->full_name ?? ($kepalaSekolah?->name ?? 'Kepala Sekolah'),
            'nip' => $kepalaSekolah?->employee?->nip ?? ($kepalaSekolah?->employee?->nuptk ?? '-')
        ];

        // Set juga property pada object $kepalaSekolah jika dikirim ke view
        if ($kepalaSekolah) {
            $kepalaSekolah->name_formatted = $kepsekData->name;
            $kepalaSekolah->nip_formatted = $kepsekData->nip;
        }

        $no_surat = $request->input('no_surat');

        return view('templates.' . $filename, compact('kepalaSekolah', 'kepsekData', 'no_surat'));
    }
}
