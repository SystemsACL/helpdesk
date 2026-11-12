<a class="tcard" href="{{ route('tickets.show', $t) }}">
  <div class="top"><span>{{ $t->ticket_number }}</span><span class="badge b-{{ $t->status->value }}">{{ $t->status->label() }}</span></div>
  <small>{{ $t->requester_name }} · {{ $t->category->name }}</small>
  <small>{{ $t->opened_at->timezone(\App\Models\CompanySetting::tz())->format('d/m/Y H:i') }}@if($t->assignee) · {{ $t->assignee->name }}@endif</small>
</a>
