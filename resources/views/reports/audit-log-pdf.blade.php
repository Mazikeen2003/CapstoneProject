<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $title }} — City Transparency Portal</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: "DejaVu Sans", "Arial Unicode MS", Helvetica, Arial, sans-serif;
  color: #1e293b;
  font-size: 9pt;
  line-height: 1.45;
  background: #ffffff;
}

/* ===== HEADER ===== */
.header {
  background: #0a1628;
  border-bottom: 4px solid #c9a84c;
  padding: 24px 28px 20px;
}

.header-tag {
  font-size: 8pt;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  color: #c9a84c;
  font-weight: bold;
  margin-bottom: 10px;
}

.header h1 {
  color: #ffffff;
  font-size: 24pt;
  font-weight: bold;
  margin-bottom: 8px;
}

.header-desc {
  color: #94a3b8;
  font-size: 9.5pt;
  margin-bottom: 12px;
}

.header-badge {
  display: inline-block;
  border: 1px solid #c9a84c;
  color: #c9a84c;
  padding: 6px 14px;
  font-size: 7.5pt;
  font-weight: bold;
  letter-spacing: 0.1em;
  text-transform: uppercase;
}

/* ===== METADATA ===== */
.meta-bar {
  background: #f1f5f9;
  border-bottom: 1px solid #cbd5e1;
  padding: 10px 28px;
}

.meta-bar table {
  width: 100%;
  border-collapse: collapse;
}

.meta-bar td {
  font-size: 8.5pt;
  color: #475569;
  padding: 2px 0;
}

.meta-bar .label {
  font-weight: bold;
  color: #0a1628;
  width: 100px;
  font-size: 7.5pt;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

/* ===== PAGE ===== */
.page {
  padding: 20px 28px 24px;
}

/* ===== STAT BAR ===== */
.stat-row {
  width: 100%;
  border-collapse: separate;
  border-spacing: 10px 0;
  margin: 0 -10px 20px;
}

.stat-row td {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-top: 3px solid #c9a84c;
  padding: 12px 14px;
  width: 25%;
  vertical-align: top;
}

.stat-num {
  font-size: 18pt;
  font-weight: bold;
  color: #0a1628;
  line-height: 1;
}

.stat-caption {
  font-size: 7.5pt;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  margin-top: 4px;
}

/* ===== TABLE ===== */
.table-wrapper {
  border: 1px solid #94a3b8;
  margin-bottom: 18px;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 8pt;
}

.data-table thead {
  background: #162347;
}

.data-table th {
  color: #ffffff;
  padding: 10px 8px;
  text-align: left;
  font-weight: bold;
  font-size: 7.5pt;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: none;
  border-bottom: 2px solid #0a1628;
}

.data-table td {
  padding: 8px 8px;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: top;
  color: #334155;
}

.data-table tbody tr:nth-child(even) {
  background: #f8fafc;
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.text-right { text-align: right; }

/* Column widths */
.col-date   { width: 11%; }
.col-user   { width: 10%; }
.col-ip     { width: 9%; }
.col-name   { width: 11%; }
.col-action { width: 8%; }
.col-table  { width: 9%; }
.col-record { width: 7%; }
.col-detail { width: 35%; }

/* Action badge */
.action-badge {
  display: inline-block;
  padding: 2px 6px;
  font-size: 7pt;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: #ffffff;
  background: #475569;
}

.action-create { background: #059669; }
.action-update { background: #2563eb; }
.action-delete { background: #dc2626; }
.action-login  { background: #d97706; }

/* Date cell */
.date-cell {
  font-family: "DejaVu Sans Mono", monospace;
  font-size: 7.5pt;
  color: #475569;
  white-space: nowrap;
}

/* User cell */
.user-cell {
  font-weight: bold;
  color: #0a1628;
  font-size: 8pt;
}

/* IP cell */
.ip-cell {
  font-family: "DejaVu Sans Mono", monospace;
  font-size: 7.5pt;
  color: #475569;
}

/* Details cell */
.details-cell {
  font-size: 7.5pt;
  line-height: 1.4;
}

.details-cell .old-label,
.details-cell .new-label {
  font-weight: bold;
  font-size: 6.5pt;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.details-cell .old-label { color: #dc2626; }
.details-cell .new-label { color: #059669; }

.details-cell .old-val { color: #991b1b; }
.details-cell .new-val { color: #065f46; }

.details-cell .field {
  color: #64748b;
  font-weight: 600;
}

.details-cell .val {
  font-family: "DejaVu Sans Mono", monospace;
  font-size: 7pt;
}

.details-cell .sep {
  color: #cbd5e1;
  margin: 0 3px;
}

.no-changes {
  color: #94a3b8;
  font-style: italic;
  font-size: 7.5pt;
}

/* ===== FOOTER ===== */
.footer {
  margin-top: 24px;
  padding: 12px 0 0;
  border-top: 2px solid #c9a84c;
  font-size: 8pt;
  color: #64748b;
  text-align: center;
  line-height: 1.5;
}

.footer strong {
  color: #0a1628;
}
</style>
</head>
<body>

@php
  $totalLogs = $logs->count();
  $createCount = $logs->where('action', 'create')->count();
  $updateCount = $logs->where('action', 'update')->count();
  $deleteCount = $logs->where('action', 'delete')->count();
  $keyFields = [
    'project_name',
    'current_status',
    'approved_budget',
    'actual_budget',
    'start_date',
    'target_end_date',
    'actual_end_date',
    'barangay_id',
  ];
  $dateFields = ['start_date', 'target_end_date', 'actual_end_date'];
@endphp

<!-- Header -->
<div class="header">
  <div class="header-tag">City of Cabuyao — Transparency Portal</div>
  <h1>{{ $title }}</h1>
  <div class="header-desc">System activity and access history audit log</div>
  <span class="header-badge">Internal Use</span>
</div>

<!-- Metadata -->
<div class="meta-bar">
  <table>
    <tr>
      <td class="label">Generated by</td>
      <td>{{ $generated_by }}</td>
      <td class="label">Date</td>
      <td>{{ $generated_date }}</td>
      <td class="label">Records</td>
      <td>{{ $totalLogs }} entries</td>
      <td class="label">Type</td>
      <td>Audit activity export</td>
    </tr>
  </table>
</div>

<div class="page">

<!-- Stats -->
<table class="stat-row">
  <tr>
    <td>
      <div class="stat-num">{{ $totalLogs }}</div>
      <div class="stat-caption">Total Entries</div>
    </td>
    <td>
      <div class="stat-num">{{ $createCount }}</div>
      <div class="stat-caption">Created</div>
    </td>
    <td>
      <div class="stat-num">{{ $updateCount }}</div>
      <div class="stat-caption">Updated</div>
    </td>
    <td>
      <div class="stat-num">{{ $deleteCount }}</div>
      <div class="stat-caption">Deleted</div>
    </td>
  </tr>
</table>

<!-- Audit Log Table -->
<div class="table-wrapper">
  <table class="data-table">
    <colgroup>
      <col class="col-date">
      <col class="col-user">
      <col class="col-ip">
      <col class="col-name">
      <col class="col-action">
      <col class="col-table">
      <col class="col-record">
      <col class="col-detail">
    </colgroup>
    <thead>
      <tr>
        <th>Date</th>
        <th>User</th>
        <th>IP Address</th>
        <th>Full Name</th>
        <th>Action</th>
        <th>Table</th>
        <th class="text-right">Record</th>
        <th>Details</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($logs as $log)
        @php
          $actionKey = strtolower($log->action);
          $actionClass = match($actionKey) {
            'create' => 'action-create',
            'update' => 'action-update',
            'delete' => 'action-delete',
            'login', 'login_failed' => 'action-login',
            default => '',
          };
          $oldFiltered = !empty($log->old_values) ? array_intersect_key((array) $log->old_values, array_flip($keyFields)) : [];
          $newFiltered = !empty($log->new_values) ? array_intersect_key((array) $log->new_values, array_flip($keyFields)) : [];
        @endphp
        <tr>
          <td class="date-cell">{{ $log->created_at?->format('M d, Y h:i A') }}</td>
          <td class="user-cell">{{ $log->user->username ?? 'Unknown' }}</td>
          <td class="ip-cell">{{ $log->ip_address ?: 'N/A' }}</td>
          <td>{{ $log->full_name ?: ($log->user->full_name ?? 'Unknown') }}</td>
          <td><span class="action-badge {{ $actionClass }}">{{ ucfirst($log->action) }}</span></td>
          <td>{{ $log->table_name }}</td>
          <td class="text-right">{{ $log->record_id }}</td>
          <td class="details-cell">
            @if(!empty($oldFiltered))
              <span class="old-label">Old:</span>
              @foreach($oldFiltered as $key => $value)
                @php
                  $display = is_array($value) ? json_encode($value) : $value;
                  if (in_array($key, $dateFields, true) && !empty($display)) {
                    $ts = strtotime($display);
                    if ($ts !== false) $display = date('M d, Y', $ts);
                  }
                @endphp
                <span class="field">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>:
                <span class="val old-val">{{ $display ?: '—' }}</span>
                @if(!$loop->last)<span class="sep">|</span>@endif
              @endforeach
              <br>
            @endif

            @if(!empty($newFiltered))
              <span class="new-label">New:</span>
              @foreach($newFiltered as $key => $value)
                @php
                  $display = is_array($value) ? json_encode($value) : $value;
                  if (in_array($key, $dateFields, true) && !empty($display)) {
                    $ts = strtotime($display);
                    if ($ts !== false) $display = date('M d, Y', $ts);
                  }
                @endphp
                <span class="field">{{ ucfirst(str_replace('_', ' ', $key)) }}</span>:
                <span class="val new-val">{{ $display ?: '—' }}</span>
                @if(!$loop->last)<span class="sep">|</span>@endif
              @endforeach
            @endif

            @if(empty($oldFiltered) && empty($newFiltered))
              <span class="no-changes">No changes recorded</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Footer -->
<div class="footer">
  <strong>City of Cabuyao — Transparency Portal</strong><br>
  Confidential system record — Generated {{ $generated_date }} by {{ $generated_by }}.
</div>

</div>

</body>
</html>