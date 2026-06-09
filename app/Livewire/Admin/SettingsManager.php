<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Title('Pengaturan Aplikasi — SI-RESERVASI PNBP')]
#[Layout('layouts.admin')]
class SettingsManager extends Component
{
    use WithFileUploads;

    public $app_name;
    public $default_theme;
    public $primary_color; // for dynamic theme color
    public $copyright_text; // for dynamic copyright
    public $footer_text; // for dynamic footer
    public $app_logo; // for upload
    public $current_logo_path;

    public function mount()
    {
        $this->app_name = AppSetting::getVal('app_name', 'SI-RESERVASI PNBP');
        $this->default_theme = AppSetting::getVal('default_theme', 'light');
        $this->primary_color = AppSetting::getVal('primary_color', '#0e1f40'); // Default Kemensos Navy
        $this->copyright_text = AppSetting::getVal('copyright_text', '© ' . date('Y') . ' SI-RESERVASI PNBP');
        $this->footer_text = AppSetting::getVal('footer_text', 'Sistem Reservasi Gedung Internal.');
        $this->current_logo_path = AppSetting::getVal('app_logo_path');
    }

    public function save()
    {
        $this->validate([
            'app_name' => 'required|string|max:100',
            'default_theme' => 'required|in:light,dark,system',
            'primary_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'copyright_text' => 'required|string|max:255',
            'footer_text' => 'required|string|max:255',
            'app_logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        AppSetting::setVal('app_name', $this->app_name);
        AppSetting::setVal('default_theme', $this->default_theme);
        AppSetting::setVal('primary_color', $this->primary_color);
        AppSetting::setVal('copyright_text', $this->copyright_text);
        AppSetting::setVal('footer_text', $this->footer_text);

        if ($this->app_logo) {
            // Delete old logo if exists
            if ($this->current_logo_path) {
                Storage::disk('public')->delete($this->current_logo_path);
            }

            $path = $this->app_logo->store('logos', 'public');
            AppSetting::setVal('app_logo_path', $path);
            $this->current_logo_path = $path;
            
            // Generate rounded favicon
            $this->generateFavicon(Storage::disk('public')->path($path));
            
            // Clear the temporary file
            $this->app_logo = null;
        }

        session()->flash('success', 'Pengaturan berhasil disimpan.');
        
        return redirect()->route('admin.settings.index');
    }

    private function generateFavicon($sourcePath)
    {
        $destinationPath = Storage::disk('public')->path('logos/favicon.png');
        $size = 64;
        $radius = 16;
        
        $info = getimagesize($sourcePath);
        if (!$info) return;
        $mime = $info['mime'];
        if ($mime == 'image/jpeg') $src = imagecreatefromjpeg($sourcePath);
        elseif ($mime == 'image/png') $src = imagecreatefrompng($sourcePath);
        else return;
        
        $srcW = imagesx($src);
        $srcH = imagesy($src);
        $dim = min($srcW, $srcH);
        $tmp = imagecreatetruecolor($size, $size);
        imagealphablending($tmp, false);
        imagesavealpha($tmp, true);
        $trans = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $trans);
        
        imagecopyresampled($tmp, $src, 0, 0, ($srcW-$dim)/2, ($srcH-$dim)/2, $size, $size, $dim, $dim);
        
        $dest = imagecreatetruecolor($size, $size);
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        imagefill($dest, 0, 0, $trans);
        
        for ($x = 0; $x < $size; $x++) {
            for ($y = 0; $y < $size; $y++) {
                $color = imagecolorat($tmp, $x, $y);
                $alpha = ($color >> 24) & 0xFF;
                
                $maskAlpha = $alpha;
                // Top Left
                if ($x < $radius && $y < $radius) {
                    $dist = sqrt(pow($radius - $x, 2) + pow($radius - $y, 2));
                    if ($dist > $radius) $maskAlpha = 127;
                }
                // Top Right
                elseif ($x >= $size - $radius && $y < $radius) {
                    $dist = sqrt(pow($x - ($size - $radius - 1), 2) + pow($radius - $y, 2));
                    if ($dist > $radius) $maskAlpha = 127;
                }
                // Bottom Left
                elseif ($x < $radius && $y >= $size - $radius) {
                    $dist = sqrt(pow($radius - $x, 2) + pow($y - ($size - $radius - 1), 2));
                    if ($dist > $radius) $maskAlpha = 127;
                }
                // Bottom Right
                elseif ($x >= $size - $radius && $y >= $size - $radius) {
                    $dist = sqrt(pow($x - ($size - $radius - 1), 2) + pow($y - ($size - $radius - 1), 2));
                    if ($dist > $radius) $maskAlpha = 127;
                }
                
                if ($maskAlpha == 127) {
                    imagesetpixel($dest, $x, $y, $trans);
                } else {
                    $rgb = imagecolorsforindex($tmp, $color);
                    $newColor = imagecolorallocatealpha($dest, $rgb['red'], $rgb['green'], $rgb['blue'], $maskAlpha);
                    imagesetpixel($dest, $x, $y, $newColor);
                }
            }
        }
        
        imagepng($dest, $destinationPath);
        imagedestroy($src);
        imagedestroy($tmp);
        imagedestroy($dest);
    }

    public function render()
    {
        return view('livewire.admin.settings-manager');
    }
}
