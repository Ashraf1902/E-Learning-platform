<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureMaterial extends Model
{
    protected $fillable = ['lecture_id', 'title', 'file_path', 'file_type', 'file_size'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    public function getFileUrlAttribute(): string
    {
        // Try the symlinked public storage path first.
        $symlinkPath = public_path('storage/' . $this->file_path);

        if (file_exists($symlinkPath)) {
            return asset('storage/' . $this->file_path);
        }

        // Fall back to the file-serving controller.
        // file_path is like: lecture-materials/5/filename.pptx
        // We pass everything after 'lecture-materials/' as the path parameter.
        $relativePath = ltrim(str_replace('lecture-materials', '', $this->file_path), '/');
        return route('files.materials', ['path' => $relativePath]);
    }

    public function getFileIconAttribute(): string
    {
        return match($this->file_type) {
            'pdf'  => '📄',
            'pptx' => '📊',
            default => '📁',
        };
    }

    public function getFileSizeLabelAttribute(): string
    {
        if (!$this->file_size) return '—';
        $size = $this->file_size;
        if ($size < 1024) return $size . ' B';
        if ($size < 1048576) return round($size / 1024, 1) . ' KB';
        return round($size / 1048576, 1) . ' MB';
    }
}