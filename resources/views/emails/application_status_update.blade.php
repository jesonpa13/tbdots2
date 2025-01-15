<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
</head>
<body>
    <h1>Status Update for {{ $facility->name }}</h1>
    <p>Dear {{ $head_of_unit }},</p> <!-- Receiver's dynamic name -->
    <p>We are writing to inform you that the application for the facility <strong>{{ $facility->name }}</strong> has been updated to the status: <strong>{{ ucfirst($status) }}</strong>.</p>
    <p>{{ $message }}</p> <!-- Custom dynamic message -->
    <p>Thank you for using NTP Manager.</p>
    <p>Best Regards,<br>{{ $senderEmail }}</p> <!-- Sender's dynamic email -->
</body>
</html>
