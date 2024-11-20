<!DOCTYPE html>
<html>
<head>
    <title>Application Status Update</title>
</head>
<body>
    <p>Dear {{ $head_of_unit->name }},</p>

    <p>We are writing to inform you about the status of the application for the facility <strong>{{ $facility->name }}</strong>.</p>

    <p><strong>Status:</strong> {{ ucfirst($status) }}</p>
    <p>{{ $message }}</p>

    <p>Best regards,<br>
    {{ $senderEmail }}</p>
</body>
</html>
