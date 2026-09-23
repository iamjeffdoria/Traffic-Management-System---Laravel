<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Verification - {{ $permit->control_no }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #111;
            margin: 0;
            padding: 24px 16px;
        }
        .card {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            text-align: center;
            padding: 24px 20px 16px;
            border-bottom: 1px solid #eee;
        }
        .header img { width: 56px; height: 56px; margin-bottom: 8px; }
        .header p { margin: 0; font-size: 12px; color: #666; }
        .header h1 { margin: 4px 0 0; font-size: 18px; }
        .status {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }
        .status.active { background: #14b8a6; }
        .status.expired { background: #dc2626; }
        .body { padding: 20px; }
        .row { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f1f1; }
        .row:last-child { border-bottom: none; }
        .row .label { color: #666; font-size: 13px; }
        .row .value { font-weight: 600; font-size: 13px; text-align: right; }
        .footer { text-align: center; padding: 14px; font-size: 11px; color: #999; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ asset('images/lgu-logo.png') }}" alt="Seal">
            <p>Municipality of Palompon — Office of the Municipal Mayor</p>
            <h1>Mayor's Permit Verification</h1>
            <span class="status {{ $permit->status }}">{{ ucfirst($permit->status) }}</span>
        </div>
        <div class="body">
            <div class="row"><span class="label">Control No.</span><span class="value">{{ $permit->control_no }}</span></div>
            <div class="row"><span class="label">Name</span><span class="value">{{ $permit->name }}</span></div>
            <div class="row"><span class="label">Business Name</span><span class="value">{{ $permit->business_name ?: 'None' }}</span></div>
            <div class="row"><span class="label">Operation</span><span class="value">{{ $permit->motorized_operation }}</span></div>
            <div class="row"><span class="label">Quarter</span><span class="value">{{ $permit->quarter }}</span></div>
            <div class="row"><span class="label">Issue Date</span><span class="value">{{ $permit->issue_date->format('M d, Y') }}</span></div>
            <div class="row"><span class="label">Expiry Date</span><span class="value">{{ $permit->expiry_date->format('M d, Y') }}</span></div>
            <div class="row"><span class="label">OR No.</span><span class="value">{{ $permit->or_no }}</span></div>
            <div class="row"><span class="label">Mayor</span><span class="value">{{ $permit->mayor }}</span></div>
        </div>
        <div class="footer">This page confirms the authenticity of the permit above.</div>
    </div>
</body>
</html>