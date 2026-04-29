@extends('layouts.admin')
@section('title','Audit Logs')
@section('page-title','Audit Trail')
@section('content')
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>ID</th><th>Action</th><th>Module</th><th>User</th><th>IP</th><th>Description</th><th>Time</th></tr></thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td><span style="color:var(--red); font-weight:700; font-size:11px;">{{ $log->action }}</span></td>
                    <td><span style="color:var(--gray); font-size:12px;">{{ $log->module }}</span></td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td style="color:var(--gray); font-size:12px; font-family:monospace;">{{ $log->ip_address }}</td>
                    <td style="color:var(--gray); font-size:12px; max-width:280px;">{{ Str::limit($log->description, 60) }}</td>
                    <td style="color:var(--gray); font-size:12px; white-space:nowrap;">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:var(--gray); padding:40px;">No logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 24px;">{{ $logs->links() }}</div>
</div>
@endsection