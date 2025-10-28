@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">

      <div class="card mb-3">
        <div class="card-header card-header-primary">
          <h4 class="card-title mb-0">Login History</h4>
          <small class="card-category">List of all login activities</small>
        </div>

        <div class="card-body">
          {{-- Optional filters --}}
          <form method="get" class="row g-2 mb-3">
            <div class="col-auto">
              <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="From">
            </div>
            <div class="col-auto">
              <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="To">
            </div>
            <div class="col-auto">
              <button class="btn btn-primary">Filter</button>
              <a href="{{ url()->current() }}" class="btn btn-light">Reset</a>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:60px">#</th>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Login</th>
                  <th>Logout</th>
                  <th>IP Address</th>
                  <th>Device</th>
                </tr>
              </thead>
              <tbody>
                @forelse($logs as $log)
                  <tr>
                    <td>{{ $loop->iteration + ($logs->currentPage()-1)*$logs->perPage() }}</td>
                    <td>{{ $log->user_id }}</td>
                    <td>{{ optional($log->user)->name ?? '—' }}</td>
                    <td>
                      {{ optional($log->login_at)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                    </td>
                    <td>
                      @if($log->logout_at)
                        {{ \Illuminate\Support\Carbon::parse($log->logout_at)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                      @else
                        <span class="badge bg-success">Still Logged In</span>
                      @endif
                    </td>
                    <td><code>{{ $log->ip_address }}</code></td>
                    <td>
                      @php
                        $ua = (string) $log->user_agent;
                        $ua = mb_strimwidth($ua, 0, 60, '…'); // long UA trim
                      @endphp
                      <span title="{{ $log->user_agent }}">{{ $ua }}</span>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted">No login activity found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          {{-- pagination --}}
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}
            </small>
            {{ $logs->links() }}
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection
