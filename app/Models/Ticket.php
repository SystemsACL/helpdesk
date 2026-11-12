<?php
namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $guarded = [];
    protected $casts = [
        'status' => TicketStatus::class,
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function category(): BelongsTo { return $this->belongsTo(ServiceCategory::class, 'category_id'); }
    public function assignee(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
    public function comments(): HasMany { return $this->hasMany(TicketComment::class)->latest('created_at'); }
    public function events(): HasMany { return $this->hasMany(TicketEvent::class)->latest('created_at'); }
}
