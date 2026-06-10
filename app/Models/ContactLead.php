<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactLead extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'company', 'message',
        'source', 'status', 'admin_notes', 'ip_address', 'user_agent',
    ];

    public const STATUSES = ['new', 'in_progress', 'responded', 'closed', 'spam'];
}
