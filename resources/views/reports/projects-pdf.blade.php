<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $title }} — City of Cabuyao</title>
<style>
/* ============================================
   CITY OF CABUYAO — PROJECT TRACKER REPORT
   Refined Edition — domPDF Optimized
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
   Navy         #162347  — table headers, badges
   Gold         #c9a84c  — primary accent, borders
   Gold Pale    #f5efd8  — soft highlights
   Slate        #475569  — secondary text
   Muted        #64748b  — captions, metadata
   Cloud        #f4f6f8  — page background
   White        #ffffff  — surface
   Border       #d1d5db  — dividers

   Status Colors:
   Ongoing   #dbeafe / #1e40af  — blue
   Completed #d1fae5 / #065f46  — green
   Delayed   #fef3c7 / #92400e  — amber
   Cancelled #fee2e2 / #991b1b  — red
   Pending   #f3f4f6 / #4b5563  — gray
   ============================================ */

/* ---- Header ---- */
.header {
  background: #0a1628;
  border-bottom: 5px solid #c9a84c;
  padding: 28px 32px 24px;
  position: relative;
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
  border: 1px solid #d1d5db;
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
.meta-table td.meta-item + td.meta-item { padding-left: 16px; }
.meta-table td.meta-item:last-child { text-align: right; padding-left: 8px; }

.meta-label {
  font-weight: bold;
  color: #0a1628;
  font-size: 8pt;
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
  border: 1px solid #d1d5db;
  border-top: 4px solid #c9a84c;
  border-radius: 6px;
  padding: 18px 18px 16px;
  width: 25%;
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

.stat-value.budget {
  font-size: 16pt;
}

.stat-context {
  font-size: 8.5pt;
  color: #64748b;
  margin-bottom: 10px;
}

.stat-bar-track {
  height: 5px;
  background: #e5e7eb;
  border-radius: 3px;
  overflow: hidden;
}

.stat-bar-fill {
  height: 100%;
  background: #c9a84c;
  border-radius: 3px;
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
}

.findings-box {
  background: #f8fafc;
  border: 1px solid #d1d5db;
  border-left: 4px solid #c9a84c;
  border-radius: 0 8px 8px 0;
  padding: 16px 20px;
  margin-bottom: 22px;
}

.findings-box h3 {
  font-size: 8pt;
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
  border: 1px solid #d1d5db;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 20px;
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
}

.data-table td {
  padding: 9px 8px;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
  color: #334155;
  overflow-wrap: break-word;
}

.data-table tbody tr:nth-child(even) {
  background: #f8fafc;
}

.data-table tbody tr:last-child td {
  border-bottom: none;
}

.text-right { text-align: right; }
.text-center { text-align: center; }

/* ---- Status Badges ---- */
.status {
  display: inline-block;
  padding: 3px 8px;
  border-radius: 4px;
  font-size: 7.5pt;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.04em;
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

.status-pending {
  background: #f3f4f6;
  color: #4b5563;
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

/* ---- Budget Utilization Bar ---- */
.util-track {
  display: inline-block;
  width: 100%;
  height: 6px;
  background: #e5e7eb;
  border-radius: 3px;
  overflow: hidden;
  vertical-align: middle;
  margin-top: 3px;
}

.util-fill {
  display: block;
  height: 100%;
  border-radius: 3px;
}

.util-fill.safe {
  background: #10b981;
}

.util-fill.warning {
  background: #f59e0b;
}

.util-fill.over {
  background: #ef4444;
}

.util-label {
  font-size: 7.5pt;
  color: #64748b;
  font-family: "DejaVu Sans Mono", monospace;
  margin-top: 2px;
}

/* ---- Summary Row ---- */
.summary-row td {
  border-top: 2px solid #0a1628;
  font-weight: bold;
  background: #f1f5f9;
  color: #0a1628;
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
.col-code     { width: 8%; }
.col-name     { width: 20%; }
.col-type     { width: 9%; }
.col-barangay { width: 10%; }
.col-status   { width: 11%; }
.col-start    { width: 10%; }
.col-target   { width: 10%; }
.col-budget   { width: 11%; }
.col-spent    { width: 11%; }
</style>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="header-eyebrow">City of Cabuyao — Transparency Portal</div>
  <h1>{{ $title }}</h1>
  <div class="header-sub">Official Project Tracker report documenting active and completed city projects</div>
  <span class="header-badge">Public Record</span>
</div>

<!-- Metadata Panel -->
<div class="meta-panel">
  <table class="meta-table">
    <tr>
      <td class="meta-item"><span class="meta-label">Generated by</span><span class="meta-value">{{ $generated_by }}</span></td>
      <td class="meta-item"><span class="meta-label">Date</span><span class="meta-value">{{ $generated_date }}</span></td>
      <td class="meta-item"><span class="meta-label">Records</span><span class="meta-value">{{ $total_projects }} projects</span></td>
    </tr>
  </table>
</div>

<div class="page">

<!-- Stat Cards -->
<table class="stat-cards-table">
  <tr>
    <td class="stat-card">
      <div class="stat-label">Total Projects</div>
      <div class="stat-value">{{ $total_projects }}</div>
      <div class="stat-context">All tracked projects</div>
      <div class="stat-bar-track">
        <div class="stat-bar-fill" style="width: 100%;"></div>
      </div>
    </td>
    <td class="stat-card">
      <div class="stat-label">Ongoing</div>
      <div class="stat-value">{{ $ongoing }}</div>
      <div class="stat-context">Currently in progress</div>
      <div class="stat-bar-track">
        <div class="stat-bar-fill" style="width: {{ $total_projects > 0 ? round(($ongoing / $total_projects) * 100) : 0 }}%;"></div>
      </div>
    </td>
    <td class="stat-card">
      <div class="stat-label">Completed</div>
      <div class="stat-value">{{ $completed }}</div>
      <div class="stat-context">Successfully closed</div>
      <div class="stat-bar-track">
        <div class="stat-bar-fill" style="width: {{ $total_projects > 0 ? round(($completed / $total_projects) * 100) : 0 }}%;"></div>
      </div>
    </td>
    <td class="stat-card">
      <div class="stat-label">Total Budget</div>
      <div class="stat-value budget">PHP&nbsp;{{ number_format($total_budget, 0) }}</div>
      <div class="stat-context">Approved allocation</div>
      <div class="stat-bar-track">
        <div class="stat-bar-fill" style="width: 100%;"></div>
      </div>
    </td>
  </tr>
</table>

<!-- Executive Summary -->
<div class="section-title">Executive Summary</div>
<div class="findings-box">
  <h3>Key Findings</h3>
  <ul class="findings-list">
    <li>A total of <strong>{{ $total_projects }}</strong> projects are currently tracked, with <strong>{{ $ongoing }}</strong> ongoing and <strong>{{ $completed }}</strong> completed.</li>
    <li>The overall project completion rate is <strong>{{ $total_projects > 0 ? round(($completed / $total_projects) * 100, 1) : 0 }}%</strong>, indicating {{ ($total_projects > 0 && ($completed / $total_projects) >= 0.7) ? 'strong' : (($total_projects > 0 && ($completed / $total_projects) >= 0.4) ? 'moderate' : 'early-stage') }} operational progress.</li>
    <li>The combined approved budget across all projects totals <strong>PHP {{ number_format($total_budget, 0) }}</strong>.</li>
    <li>All project data is published as public record in accordance with the City Transparency Portal mandate.</li>
  </ul>
</div>

<!-- Projects Table -->
<div class="section-title">Project Registry</div>

<div class="table-wrapper">
  <table class="data-table">
    <colgroup>
      <col class="col-code">
      <col class="col-name">
      <col class="col-type">
      <col class="col-barangay">
      <col class="col-status">
      <col class="col-start">
      <col class="col-target">
      <col class="col-budget">
      <col class="col-spent">
    </colgroup>
    <thead>
      <tr>
        <th>Code</th>
        <th>Project Name</th>
        <th>Type</th>
        <th>Barangay</th>
        <th>Status</th>
        <th>Start Date</th>
        <th>Target End</th>
        <th class="text-right">Budget</th>
        <th class="text-right">Spent</th>
      </tr>
    </thead>
    <tbody>
      @php
        $grandBudget = 0;
        $grandSpent = 0;
      @endphp
      @foreach ($projects as $project)
        @php
          $grandBudget += $project['budget'];
          $grandSpent += $project['spent'];
          $statusClass = match(strtolower($project['status'])) {
            'ongoing', 'in progress' => 'status-ongoing',
            'completed', 'done', 'finished' => 'status-completed',
            'delayed', 'postponed' => 'status-delayed',
            'cancelled', 'terminated' => 'status-cancelled',
            default => 'status-pending',
          };
          $utilization = $project['budget'] > 0 ? ($project['spent'] / $project['budget']) * 100 : 0;
          $utilClass = $utilization > 100 ? 'over' : ($utilization > 85 ? 'warning' : 'safe');
        @endphp
        <tr>
          <td><strong>{{ $project['code'] }}</strong></td>
          <td>{{ $project['name'] }}</td>
          <td>{{ $project['type'] }}</td>
          <td>{{ $project['barangay'] }}</td>
          <td><span class="status {{ $statusClass }}">{{ $project['status'] }}</span></td>
          <td>{{ $project['start_date'] }}</td>
          <td>{{ $project['end_date'] }}</td>
          <td class="text-right"><span class="money">{{ number_format($project['budget'], 2) }}</span></td>
          <td class="text-right">
            <span class="money {{ $utilClass == 'over' ? 'money-spent' : '' }}">{{ number_format($project['spent'], 2) }}</span>
            <div class="util-track">
              <span class="util-fill {{ $utilClass }}" style="width: {{ min(round($utilization), 100) }}%;"></span>
            </div>
            <div class="util-label">{{ round($utilization, 1) }}%</div>
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