@foreach($logs as $log)
    <div class="p-6 hover:bg-gray-50 transition duration-150 flex flex-col md:flex-row gap-4 items-start md:items-center">
        <div class="min-w-[180px] text-sm text-gray-500 font-medium">
            {{ $log->created_at->format('d/m/Y, h:i:s a') }}
        </div>
        
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-1">
                @php
                    $badgeColor = 'bg-gray-100 text-gray-600';
                    $icon = 'fa-info-circle';
                    
                    if ($log->type == 'auth') {
                        $badgeColor = 'bg-blue-100 text-blue-700';
                        $icon = 'fa-key';
                    } elseif ($log->type == 'survey') {
                        $badgeColor = 'bg-green-100 text-green-700';
                        $icon = 'fa-clipboard-list';
                    } elseif ($log->type == 'user') {
                        $badgeColor = 'bg-purple-100 text-purple-700';
                        $icon = 'fa-user';
                    }
                @endphp
                
                <span class="{{ $badgeColor }} text-xs font-bold px-2 py-1 rounded capitalize flex items-center gap-1">
                    <i class="fas {{ $icon }}"></i> {{ $log->type }}
                </span>
                
                <span class="font-medium text-gray-800">{{ $log->description }}</span>
            </div>
            <div class="text-xs text-gray-400">
                <span class="font-bold text-gray-600">{{ $log->user ? $log->user->name : 'Usuario Eliminado' }}</span> 
                <span class="mx-1">•</span> 
                {{ $log->user_email ?? 'Sistema' }} 
                <span class="mx-1">•</span> 
                IP: {{ $log->ip_address }}
            </div>
        </div>
    </div>
@endforeach

<div class="p-4 border-t border-gray-100 bg-gray-50">
    {{ $logs->appends(request()->query())->links() }}
</div>