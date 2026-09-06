<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Budget Analysis Report — City of Cabuyao</title>
<style>
/* ============================================
   CITY OF CABUYAO — BUDGET ANALYSIS REPORT
   domPDF Hardened Edition
   ============================================ */

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: "DejaVu Sans", "Arial Unicode MS", Helvetica, Arial, sans-serif;
  color: #1e293b;
  font-size: 10pt;
  line-height: 1.5;
  background: #ffffff;
}

/* ============================================
   COLOR SYSTEM
   --------------------------------------------
   Deep Navy    #0a1628  — authority, headers
   Navy         #162347  — table headers
   Gold         #c9a84c  — primary accent
   Slate        #475569  — secondary text
   Muted        #64748b  — captions
   Cloud        #f8fafc  — soft backgrounds
   Border       #94a3b8  — visible dividers
   ============================================ */

/* ---- Header ---- */
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

.header-badge {
  display: inline-block;
  background: #162347;
  border: 1px solid #c9a84c;
  color: #c9a84c;
  padding: 6px 12px;
  font-size: 7.5pt;
  font-weight: bold;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-top: 10px;
}

/* ---- Metadata Panel ---- */
.meta-panel {
  background: #f8fafc;
  border: 1px solid #94a3b8;
  border-top: none;
  padding: 14px 32px;
}

.meta-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

.meta-table td {
  padding: 4px 0;
  border: none;
  vertical-align: top;
  font-size: 9pt;
  color: #475569;
  white-space: nowrap;
}

.meta-table td.meta-item { width: 33.333%; }
.meta-table td.meta-item + td.meta-item { padding-left: 18px; }
.meta-table td.meta-item:last-child { text-align: right; padding-left: 8px; }

.meta-label {
  font-weight: bold;
  color: #0a1628;
  font-size: 8.5pt;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  white-space: nowrap;
}

.meta-value {
  color: #475569;
  margin-left: 7px;
  white-space: nowrap;
}

/* ---- Page Container ---- */
.page {
  padding: 0 32px 24px;
}

/* ---- Stat Cards ---- */
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
  font-size: 18pt;
  font-weight: bold;
  color: #0a1628;
  letter-spacing: -0.02em;
  line-height: 1;
  margin-bottom: 6px;
}

.stat-context {
  font-size: 8.5pt;
  color: #64748b;
  margin-bottom: 10px;
}

/* Progress bar using table for domPDF safety */
.stat-bar {
  width: 100%;
  border-collapse: collapse;
  height: 5px;
}

.stat-bar td {
  padding: 0;
  border: none;
  height: 5px;
  font-size: 0;
  line-height: 0;
}

.stat-bar-fill {
  background: #c9a84c;
}

.stat-bar-empty {
  background: #e2e8f0;
}

/* ---- Executive Summary ---- */
.section-title {
  margin-top: 28px;
  margin-bottom: 14px;
  padding: 0 0 0 14px;
  border-left: 4px solid #c9a84c;
  font-size: 13pt;
  font-weight: bold;
  color: #0a1628;
  letter-spacing: -0.01em;
  page-break-after: avoid;
}

.section-desc {
  font-size: 9.5pt;
  color: #64748b;
  margin: -8px 0 16px 18px;
  line-height: 1.4;
  page-break-after: avoid;
}

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

/* ---- Table Wrapper ---- */
.table-wrapper {
  border: 1px solid #94a3b8;
  margin-bottom: 20px;
  page-break-inside: avoid;
  page-break-before: avoid;
}

/* ---- Data Table ---- */
.data-table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
  font-size: 8.5pt;
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
  letter-spacing: 0.06em;
  border: none;
  border-bottom: 2px solid #0a1628;
}

.data-table th.text-right { text-align: right; }

.data-table td {
  padding: 10px 8px;
  border-bottom: 1px solid #cbd5e1;
  vertical-align: middle;
  color: #334155;
  overflow-wrap: break-word;
}

.data-table tbody tr {
  background: #ffffff;
  page-break-inside: avoid;
}

.data-table tbody tr:nth-child(even) {
  background: #f8fafc;
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.text-right { text-align: right; }

/* ---- Status Badges ---- */
.status {
  display: inline-block;
  padding: 4px 10px;
  font-size: 7.5pt;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  line-height: 1.4;
  vertical-align: middle;
}

.status-ongoing {
  background: #dbeafe;
  color: #1e40af;
}

.status-completed {
  background: #d1fae5;
  color: #065f46;
}

.status-delayed {
  background: #fef3c7;
  color: #92400e;
}

.status-cancelled {
  background: #fee2e2;
  color: #991b1b;
}

.status-pending,
.status-for-bidding {
  background: #f3f4f6;
  color: #4b5563;
  border: 1px solid #d1d5db;
}

/* ---- Money / Numbers ---- */
.money {
  font-family: "DejaVu Sans Mono", "Courier New", monospace;
  font-size: 8pt;
  font-weight: bold;
  white-space: nowrap;
  color: #0a1628;
}

.money-spent {
  color: #991b1b;
}

.money-balance {
  color: #065f46;
}

/* ---- Utilization Bar (table-based for domPDF) ---- */
.util-table {
  width: 100%;
  border-collapse: collapse;
  height: 8px;
}

.util-table td {
  padding: 0;
  border: none;
  height: 8px;
  font-size: 0;
  line-height: 0;
}

.util-fill-safe {
  background: #10b981;
}

.util-fill-warning {
  background: #f59e0b;
}

.util-fill-over {
  background: #ef4444;
}

.util-empty {
  background: #e2e8f0;
}

.util-label {
  font-size: 7.5pt;
  color: #64748b;
  font-family: "DejaVu Sans Mono", monospace;
  margin-top: 4px;
  text-align: right;
}

/* ---- Footer ---- */
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

/* ---- Column Widths ---- */
.col-label  { width: 24%; }
.col-count  { width: 10%; }
.col-money  { width: 17%; }
.col-bar    { width: 15%; }
</style>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="header-eyebrow">City of Cabuyao — Transparency Portal</div>
  <h1>Budget Analysis Report</h1>
  <div class="header-sub">Official financial breakdown of all city projects by status and barangay</div>
  <span class="header-badge">Public Record</span>
</div>

<!-- Metadata Panel -->
<div class="meta-panel">
  <table class="meta-table">
    <tr>
      <td class="meta-item"><span class="meta-label">Generated by</span><span class="meta-value">{{ $generated_by }}</span></td>
      <td class="meta-item"><span class="meta-label">Date</span><span class="meta-value">{{ $generated_date }}</span></td>
      <td class="meta-item"><span class="meta-label">Total Budget</span><span class="meta-value">PHP {{ number_format($total_budget, 2) }}</span></td>
    </tr>
  </table>
</div>

<div class="page">

@php
  $totalRemaining = $total_budget - $total_spent;
  $utilizationRate = $total_budget > 0 ? ($total_spent / $total_budget) * 100 : 0;
  $utilClass = $utilizationRate > 100 ? 'over' : ($utilizationRate > 85 ? 'warning' : 'safe');
@endphp

<!-- Stat Cards -->
<table class="stat-cards-table">
  <tr>
    <td class="stat-card">
      <div class="stat-label">Total Budget Allocated</div>
      <div class="stat-value">PHP&nbsp;{{ number_format($total_budget, 0) }}</div>
      <div class="stat-context">Approved across all projects</div>
      <table class="stat-bar"><tr>
        <td class="stat-bar-fill" style="width: 100%"></td>
        <td class="stat-bar-empty" style="width: 0%"></td>
      </tr></table>
    </td>
    <td class="stat-card">
      <div class="stat-label">Total Budget Spent</div>
      <div class="stat-value">PHP&nbsp;{{ number_format($total_spent, 0) }}</div>
      <div class="stat-context">Recorded expenditures</div>
      <table class="stat-bar"><tr>
        <td class="stat-bar-fill" style="width: {{ min(round($utilizationRate), 100) }}%"></td>
        <td class="stat-bar-empty" style="width: {{ 100 - min(round($utilizationRate), 100) }}%"></td>
      </tr></table>
    </td>
    <td class="stat-card">
      <div class="stat-label">Remaining Balance</div>
      <div class="stat-value">PHP&nbsp;{{ number_format($totalRemaining, 0) }}</div>
      <div class="stat-context">Unspent funds available</div>
      <table class="stat-bar"><tr>
        <td class="stat-bar-fill" style="width: {{ $total_budget > 0 ? min(round(($totalRemaining / $total_budget) * 100), 100) : 0 }}%"></td>
        <td class="stat-bar-empty" style="width: {{ $total_budget > 0 ? 100 - min(round(($totalRemaining / $total_budget) * 100), 100) : 100 }}%"></td>
      </tr></table>
    </td>
  </tr>
</table>

<!-- Executive Summary -->
<div class="section-title">Executive Summary</div>
<div class="findings-box">
  <h3>Key Findings</h3>
  <ul class="findings-list">
    <li>The total approved budget across all projects is <strong>PHP {{ number_format($total_budget, 2) }}</strong>, of which <strong>PHP {{ number_format($total_spent, 2) }}</strong> has been spent.</li>
    <li>The overall budget utilization rate is <strong>{{ round($utilizationRate, 1) }}%</strong>, which is considered {{ $utilClass == 'safe' ? 'healthy' : ($utilClass == 'warning' ? 'approaching threshold' : 'over budget') }}.</li>
    <li>The remaining balance of <strong>PHP {{ number_format($totalRemaining, 2) }}</strong> represents {{ $total_budget > 0 ? round(($totalRemaining / $total_budget) * 100, 1) : 0 }}% of the total approved allocation.</li>
    <li>All financial data is published as public record in accordance with the City Transparency Portal mandate.</li>
  </ul>
</div>

<!-- Breakdown by Status -->
<div class="section-title">Breakdown by Status</div>
<p class="section-desc">Financial distribution across project statuses with utilization indicators.</p>

<div class="table-wrapper">
  <table class="data-table">
    <colgroup>
      <col class="col-label">
      <col class="col-count">
      <col class="col-money">
      <col class="col-money">
      <col class="col-money">
      <col class="col-bar">
    </colgroup>
    <thead>
      <tr>
        <th>Status</th>
        <th class="text-right">Count</th>
        <th class="text-right">Budget</th>
        <th class="text-right">Spent</th>
        <th class="text-right">Balance</th>
        <th>Utilization</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($by_status as $status => $stats)
        @php
          $statusUtil = $stats['budget'] > 0 ? ($stats['spent'] / $stats['budget']) * 100 : 0;
          $statusKey = strtolower($status);
          $sClass = 'status-pending';
          if (in_array($statusKey, ['ongoing', 'in progress'])) $sClass = 'status-ongoing';
          elseif (in_array($statusKey, ['completed', 'done', 'finished'])) $sClass = 'status-completed';
          elseif (in_array($statusKey, ['delayed', 'postponed'])) $sClass = 'status-delayed';
          elseif (in_array($statusKey, ['cancelled', 'terminated'])) $sClass = 'status-cancelled';
          elseif (in_array($statusKey, ['for bidding', 'for_bidding', 'bidding'])) $sClass = 'status-for-bidding';

          $fillWidth = min(round($statusUtil), 100);
          $emptyWidth = 100 - $fillWidth;

          if ($statusUtil > 100) {
            $fillClass = 'util-fill-over';
          } elseif ($statusUtil > 85) {
            $fillClass = 'util-fill-warning';
          } else {
            $fillClass = 'util-fill-safe';
          }
        @endphp
        <tr>
          <td><span class="status {{ $sClass }}">{{ $status }}</span></td>
          <td class="text-right">{{ $stats['count'] }}</td>
          <td class="text-right"><span class="money">{{ number_format($stats['budget'], 2) }}</span></td>
          <td class="text-right"><span class="money {{ $statusUtil > 100 ? 'money-spent' : '' }}">{{ number_format($stats['spent'], 2) }}</span></td>
          <td class="text-right"><span class="money money-balance">{{ number_format($stats['budget'] - $stats['spent'], 2) }}</span></td>
          <td>
            <table class="util-table"><tr>
              <td class="{{ $fillClass }}" style="width: {{ $fillWidth }}%"></td>
              <td class="util-empty" style="width: {{ $emptyWidth }}%"></td>
            </tr></table>
            <div class="util-label">{{ round($statusUtil, 1) }}%</div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Breakdown by Barangay -->
<div class="section-title">Breakdown by Barangay</div>
<p class="section-desc">Financial distribution across barangays with proportional budget bars.</p>

<div class="table-wrapper">
  <table class="data-table">
    <colgroup>
      <col class="col-label">
      <col class="col-count">
      <col class="col-money">
      <col class="col-money">
      <col class="col-money">
      <col class="col-bar">
    </colgroup>
    <thead>
      <tr>
        <th>Barangay</th>
        <th class="text-right">Count</th>
        <th class="text-right">Budget</th>
        <th class="text-right">Spent</th>
        <th class="text-right">Balance</th>
        <th>Utilization</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($by_barangay as $barangay => $stats)
        @php
          $barangayUtil = $stats['budget'] > 0 ? ($stats['spent'] / $stats['budget']) * 100 : 0;
          $bFillWidth = min(round($barangayUtil), 100);
          $bEmptyWidth = 100 - $bFillWidth;

          if ($barangayUtil > 100) {
            $bFillClass = 'util-fill-over';
          } elseif ($barangayUtil > 85) {
            $bFillClass = 'util-fill-warning';
          } else {
            $bFillClass = 'util-fill-safe';
          }
        @endphp
        <tr>
          <td><strong>{{ $barangay }}</strong></td>
          <td class="text-right">{{ $stats['count'] }}</td>
          <td class="text-right"><span class="money">{{ number_format($stats['budget'], 2) }}</span></td>
          <td class="text-right"><span class="money {{ $barangayUtil > 100 ? 'money-spent' : '' }}">{{ number_format($stats['spent'], 2) }}</span></td>
          <td class="text-right"><span class="money money-balance">{{ number_format($stats['budget'] - $stats['spent'], 2) }}</span></td>
          <td>
            <table class="util-table"><tr>
              <td class="{{ $bFillClass }}" style="width: {{ $bFillWidth }}%"></td>
              <td class="util-empty" style="width: {{ $bEmptyWidth }}%"></td>
            </tr></table>
            <div class="util-label">{{ round($barangayUtil, 1) }}%</div>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- Footer -->
<div class="footer">
  <strong>City of Cabuyao — Transparency Portal</strong><br>
  This is an official report generated by the City Transparency Portal. All data is published as public record.<br>
  Generated on {{ $generated_date }} by {{ $generated_by }}.
</div>

</div>

</body>
</html>