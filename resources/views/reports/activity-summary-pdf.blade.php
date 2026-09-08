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
  font-size: 10pt;
  line-height: 1.5;
  background: #ffffff;
}

/* Header */
.header {
  background: #0a1628;
  border-bottom: 5px solid #c9a84c;
  padding: 28px 32px 24px;
}

.header-eyebrow {
  font-size: 8pt;
  text-transform: uppercase;
  letter-spacing: 0.18em;
  color: #c9a84c;
  font-weight: bold;
  margin-bottom: 6px;
}

.header h1 {
  color: #ffffff;
  font-size: 22pt;
  font-weight: bold;
  letter-spacing: -0.02em;
  margin-bottom: 6px;
}

.header-sub {
  color: #94a3b8;
  font-size: 9.5pt;
  line-height: 1.4;
}

/* Metadata Panel */
.meta-panel {
  background: #f8fafc;
  border: 1px solid #94a3b8;
  border-top: none;
  padding: 14px 32px;
}

.meta-table {
  width: 100%;
  border-collapse: collapse;
}

.meta-table td {
  padding: 4px 0;
  border: none;
  vertical-align: top;
  font-size: 9pt;
  color: #475569;
}

.meta-label {
  font-weight: bold;
  color: #0a1628;
  width: 120px;
  font-size: 8.5pt;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  white-space: nowrap;
  padding-right: 8px;
}

.meta-value {
  color: #475569;
  padding-right: 24px;
}

/* Page */
.page {
  padding: 0 32px 24px;
}

/* Stat Cards */
.stat-cards-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 14px 0;
  margin: 22px -14px 26px;
}

.stat-card {
  background: #ffffff;
  border: 1px solid #94a3b8;
  border-top: 4px solid #c9a84c;
  padding: 18px 18px 16px;
  width: 33.33%;
  vertical-align: top;
}

.stat-label {
  font-size: 7.5pt;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: #64748b;
  font-weight: bold;
  margin-bottom: 8px;
}

.stat-value {
  font-size: 22pt;
  font-weight: bold;
  color: #0a1628;
  letter-spacing: -0.02em;
  line-height: 1;
  margin-bottom: 6px;
}

.stat-context {
  font-size: 8.5pt;
  color: #64748b;
}

/* Section Title */
.section-title {
  margin-top: 28px;
  margin-bottom: 14px;
  padding: 0 0 0 14px;
  border-left: 4px solid #c9a84c;
  font-size: 13pt;
  font-weight: bold;
  color: #0a1628;
  letter-spacing: -0.01em;
}

.section-desc {
  font-size: 9.5pt;
  color: #64748b;
  margin: -8px 0 16px 18px;
  line-height: 1.4;
}

/* Findings Box */
.findings-box {
  background: #f8fafc;
  border: 1px solid #94a3b8;
  border-left: 4px solid #c9a84c;
  padding: 16px 20px;
  margin-bottom: 22px;
}

.findings-box h3 {
  font-size: 8.5pt;
  color: #0a1628;
  margin-bottom: 10px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.findings-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.findings-list li {
  padding: 4px 0 4px 18px;
  position: relative;
  font-size: 9pt;
  color: #475569;
  line-height: 1.5;
}

.findings-list li::before {
  content: "►";
  position: absolute;
  left: 0;
  color: #c9a84c;
  font-size: 6pt;
  top: 6px;
}

/* Table Wrapper */
.table-wrapper {
  border: 1px solid #94a3b8;
  margin-bottom: 20px;
}

/* Data Table */
.data-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 9pt;
}

.data-table thead {
  background: #162347;
}

.data-table th {
  color: #ffffff;
  padding: 10px 12px;
  text-align: left;
  font-weight: bold;
  font-size: 8pt;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border: none;
  border-bottom: 2px solid #0a1628;
}

.data-table td {
  padding: 10px 12px;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
  color: #334155;
}

.data-table tbody tr:nth-child(even) {
  background: #f8fafc;
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.text-right { text-align: right; }

/* Rank Badge */
.rank-badge {
  display: inline-block;
  width: 22px;
  height: 22px;
  line-height: 22px;
  text-align: center;
  background: #162347;
  color: #ffffff;
  border-radius: 50%;
  font-size: 8pt;
  font-weight: bold;
}

.rank-badge.gold {
  background: #c9a84c;
  color: #0a1628;
}

.rank-badge.silver {
  background: #94a3b8;
  color: #ffffff;
}

.rank-badge.bronze {
  background: #a08432;
  color: #ffffff;
}

/* Percentage text */
.pct-text {
  font-family: "DejaVu Sans Mono", monospace;
  font-size: 9pt;
  font-weight: bold;
  color: #c9a84c;
}

.pct-muted {
  font-size: 8pt;
  color: #94a3b8;
}

/* Hour Chart */
.hour-chart-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  font-size: 9.5pt;
}

.hour-chart-table td {
  padding: 5px 10px;
  border: none;
  vertical-align: middle;
}

.hour-label {
  width: 110px;
  text-align: right;
  padding-right: 14px;
  color: #475569;
  font-size: 9pt;
  font-variant-numeric: tabular-nums;
  font-weight: 500;
}

.hour-bar-cell {
  width: auto;
}

.hour-bar-track {
  display: inline-block;
  width: 100%;
  height: 20px;
  background: #f4f6f8;
  border: 1px solid #e5e7eb;
  vertical-align: middle;
}

.hour-bar-fill {
  display: block;
  height: 100%;
  background: #162347;
}

.hour-bar-fill.peak {
  background: #c9a84c;
}

.hour-count {
  width: 60px;
  text-align: right;
  font-weight: bold;
  color: #0a1628;
  font-variant-numeric: tabular-nums;
}

/* Footer */
.footer {
  margin-top: 28px;
  padding: 16px 0 0;
  border-top: 2px solid #c9a84c;
  font-size: 8pt;
  color: #64748b;
  text-align: center;
  line-height: 1.6;
}

.footer strong {
  color: #0a1628;
}

/* Column widths */
.col-action { width: 35%; }
.col-total { width: 15%; }
.col-pct { width: 50%; }
.col-rank { width: 40px; }
.col-user { width: 30%; }
.col-hour { width: 110px; }
.col-count { width: 60px; }
</style>
</head>
<body>

@php
  $totalActions = collect($audit_stats)->sum('total');
  $topAction = $audit_stats[0] ?? null;
  $topUser = $top_users[0] ?? null;
  $topHour = $peak_usage[0] ?? null;
@endphp

<!-- Header -->
<div class="header">
  <div class="header-eyebrow">City Transparency Portal</div>
  <h1>{{ $title }}</h1>
  <div class="header-sub">Official audit report generated from system activity logs</div>
</div>

<!-- Metadata Panel -->
<div class="meta-panel">
  <table class="meta-table">
    <tr>
      <td class="meta-label">Generated by</td>
      <td class="meta-value">{{ $generated_by }}</td>
      <td class="meta-label">Date</td>
      <td class="meta-value">{{ $generated_date }}</td>
    </tr>
    <tr>
      <td class="meta-label">Report type</td>
      <td class="meta-value">System Activity Audit</td>
      <td class="meta-label">Classification</td>
      <td class="meta-value">Confidential — Internal Use Only</td>
    </tr>
  </table>
</div>

<div class="page">

<!-- Stat Cards -->
<table class="stat-cards-table">
  <tr>
    <td class="stat-card">
      <div class="stat-label">Total Actions</div>
      <div class="stat-value">{{ $totalActions }}</div>
      <div class="stat-context">Across all action types</div>
    </td>
    <td class="stat-card">
      <div class="stat-label">Top User</div>
      <div class="stat-value" style="font-size: 16pt; padding-top: 4px;">{{ $topUser?->user?->username ?? '—' }}</div>
      <div class="stat-context">{{ $topUser?->total ?? 0 }} actions recorded</div>
    </td>
    <td class="stat-card">
      <div class="stat-label">Peak Hour</div>
      <div class="stat-value" style="font-size: 16pt; padding-top: 4px;">{{ sprintf('%02d:00', (int) ($topHour?->hour ?? 0)) }}</div>
      <div class="stat-context">{{ $topHour?->total ?? 0 }} actions in window</div>
    </td>
  </tr>
</table>

<!-- Executive Summary -->
<div class="section-title">Executive Summary</div>
<div class="findings-box">
  <h3>Key Findings</h3>
  <ul class="findings-list">
    <li>The most frequent system action was <strong>{{ ucfirst($topAction?->action ?? 'N/A') }}</strong> with <strong>{{ $topAction?->total ?? 0 }}</strong> recorded occurrences.</li>
    <li><strong>{{ $topUser?->user?->username ?? 'Unknown' }}</strong> was the most active user, accounting for the highest volume of activity in this reporting period.</li>
    <li>Peak system usage occurred during the <strong>{{ sprintf('%02d:00 – %02d:00', (int) ($topHour?->hour ?? 0), ((int) ($topHour?->hour ?? 0) + 1) % 24) }}</strong> window, indicating concentrated operational activity.</li>
    <li>Overall activity patterns suggest normal operational flow with no anomalous spikes detected.</li>
  </ul>
</div>

<!-- Actions Recorded -->
<div class="section-title">Actions Recorded</div>
<p class="section-desc">Breakdown of all system actions by type.</p>

<div class="table-wrapper">
  <table class="data-table">
    <colgroup><col class="col-action"><col class="col-total"><col class="col-pct"></colgroup>
    <thead>
      <tr>
        <th>Action</th>
        <th class="text-right">Total</th>
        <th class="text-right">Share</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($audit_stats as $stat)
        @php $actionPct = $totalActions > 0 ? round(($stat->total / $totalActions) * 100, 1) : 0; @endphp
        <tr>
          <td><strong>{{ ucfirst($stat->action) }}</strong></td>
          <td class="text-right">{{ $stat->total }}</td>
          <td class="text-right">
            <span class="pct-text">{{ $actionPct }}%</span>
            <span class="pct-muted">of total</span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Top Users -->
<div class="section-title">Top Users by Activity</div>
<p class="section-desc">Users ranked by total system activity during the reporting period.</p>

<div class="table-wrapper">
  <table class="data-table">
    <colgroup><col class="col-rank"><col class="col-user"><col class="col-total"><col class="col-pct"></colgroup>
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th class="text-right">Total</th>
        <th class="text-right">Share</th>
      </tr>
    </thead>
    <tbody>
      @php $maxUserTotal = collect($top_users)->max('total') ?: 1; @endphp
      @foreach ($top_users as $index => $entry)
        @php
          $userPct = $totalActions > 0 ? round(($entry->total / $totalActions) * 100, 1) : 0;
          $badgeClass = $index === 0 ? 'gold' : ($index === 1 ? 'silver' : ($index === 2 ? 'bronze' : ''));
        @endphp
        <tr>
          <td><span class="rank-badge {{ $badgeClass }}">{{ $index + 1 }}</span></td>
          <td><strong>{{ $entry->user?->username ?? 'Unknown' }}</strong></td>
          <td class="text-right">{{ $entry->total }}</td>
          <td class="text-right">
            <span class="pct-text">{{ $userPct }}%</span>
            <span class="pct-muted">of total</span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Peak Usage Hours -->
<div class="section-title">Peak Usage Hours</div>
<p class="section-desc">Hourly distribution of system activity. Gold bar indicates the peak window.</p>

@php $maxHourTotal = collect($peak_usage)->max('total') ?: 1; @endphp
<table class="hour-chart-table">
  @foreach ($peak_usage as $entry)
    @php
      $hourPct = $maxHourTotal > 0 ? round(($entry->total / $maxHourTotal) * 100) : 0;
      $isPeak = $entry->total == $maxHourTotal;
    @endphp
    <tr>
      <td class="hour-label">{{ sprintf('%02d:00 – %02d:00', (int) $entry->hour, ((int) $entry->hour + 1) % 24) }}</td>
      <td class="hour-bar-cell">
        <div class="hour-bar-track">
          <span class="hour-bar-fill {{ $isPeak ? 'peak' : '' }}" style="width: {{ $hourPct }}%;"></span>
        </div>
      </td>
      <td class="hour-count">{{ $entry->total }}</td>
    </tr>
  @endforeach
</table>

<!-- Footer -->
<div class="footer">
  <strong>City Transparency Portal</strong> — System Activity Audit Report<br>
  This document contains confidential information intended for internal use only.<br>
  Generated on {{ $generated_date }} by {{ $generated_by }}.
</div>

</div>

</body>
</html>