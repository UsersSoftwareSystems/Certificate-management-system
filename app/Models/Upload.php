<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    use HasFactory;
    
    /**
     * The "booting" method of the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Add a global scope to only include uploads with existing applicants
        static::addGlobalScope('hasApplicant', function (Builder $builder) {
            $builder->has('applicant');
        });

        // Delete the actual file from storage when the upload record is deleted
        static::deleting(function($upload) {
            $upload->deleteFile();
        });
        
        // Log when an upload is created or updated
        static::saved(function($upload) {
            if ($upload->wasRecentlyCreated) {
                $upload->logAction('upload_created');
            } else if ($upload->wasChanged()) {
                $upload->logAction('upload_updated');
            }
        });
    }
    


    protected $fillable = [
        'applicant_id',
        'type',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_hash',
        'mime_type',
        'file_size',
        'verification_status',
        'verifier_id',
        'verification_comments',  
        'verified_at',
        'uploader_ip'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'file_size' => 'integer'
    ];

    /**
     * Get the applicant that owns the upload.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class)->withTrashed();
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function getFileUrl(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeFormatted(): string
    {
        return $this->formatBytes($this->file_size);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
    
    /**
     * Delete the physical file from storage
     */
    public function deleteFile(): bool
    {
        $deleted = false;
        
        // Delete the main file if it exists
        if ($this->file_path && Storage::exists($this->file_path)) {
            $deleted = Storage::delete($this->file_path);
        }
        
        // Also delete any thumbnails if they exist
        if ($this->file_path) {
            $pathInfo = pathinfo($this->file_path);
            $thumbnailPath = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];
            if (Storage::exists($thumbnailPath)) {
                Storage::delete($thumbnailPath);
            }
        }
        
        return $deleted;
    }
    
    /**
     * Log an action for this upload
     */
    protected function logAction(string $action): void
    {
        if (!class_exists('\App\Models\AuditLog')) {
            return;
        }
        
        $metadata = [
            'upload_id' => $this->id,
            'file_name' => $this->original_filename,
            'file_type' => $this->mime_type,
            'file_size' => $this->file_size,
            'verification_status' => $this->verification_status,
        ];
        
        if ($this->verifier) {
            $metadata['verifier_id'] = $this->verifier_id;
        }
        
        if ($this->applicant) {
            $metadata['applicant_id'] = $this->applicant_id;
            $metadata['applicant_name'] = $this->applicant->name;
        }
        
        try {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'target_type' => get_class($this),
                'target_id' => $this->id,
                'metadata' => $metadata,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the operation
            \Log::error('Failed to log upload action: ' . $e->getMessage());
        }
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'tenth' => '10th Certificate',
            'twelfth' => '12th Certificate', 
            'graduation' => 'Graduation Certificate',
            'masters' => 'Master\'s Certificate',
            'sports' => 'Sports Achievement Certificate',
            'extraordinary' => 'Extraordinary Achievement / Other',
            default => 'Unknown'
        };
    }

    private function formatBytes($size, $precision = 2): string
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        return $size;
    }
}
