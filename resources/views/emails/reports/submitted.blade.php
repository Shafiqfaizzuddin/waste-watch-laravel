<x-mail::message>
# New Waste Report Submitted

Hello Administrator,

A new waste report has been submitted by a citizen. Please review the details below and take the necessary action.

<x-mail::panel>
### 📋 Report Details
* **Report ID:** #{{ $report->id }}
* **Title:** {{ $report->title }}
* **Category:** {{ ucfirst($report->category) }}
* **Date of Incident:** {{ \Carbon\Carbon::parse($report->date_of_incident)->format('d M Y') }}
* **Location:** {{ $report->address }}, {{ $report->city }}, {{ $report->state }}
</x-mail::panel>

### 👤 Submitter Details
* **Name:** {{ $report->user ? ($report->user->first_name . ' ' . $report->user->last_name) : 'N/A' }}
* **Email:** {{ $report->user ? $report->user->email : 'N/A' }}
* **Phone:** {{ $report->user ? $report->user->phone : 'N/A' }}

### 💬 Description
{{ $report->description }}

<x-mail::button :url="route('admin.reports.show', $report->id)">
Review Report Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
