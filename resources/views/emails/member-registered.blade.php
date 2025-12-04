<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3D2817;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .highlight {
            background-color: #FFDA9E;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #3D2817;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to Aden Africa Network!</h1>
    </div>
    
    <div class="content">
        <p>Hi {{ $member->name }},</p>
        
        <p>Thank you for joining Aden Africa Network!</p>
        
        {{-- <div class="highlight">
            <strong>Membership Type:</strong> {{ $membershipType }}
        </div> --}}
        
        @if($member->register_type === 0)
            <p>You've successfully joined our network. You now have access to:</p>
            <ul>
                <li>Network updates and newsletters</li>
                <li>Community events and webinars</li>
                <li>Networking opportunities</li>
            </ul>
        @elseif($member->register_type === 1)
            <p>Your download membership is now active!</p>
            <p>You can now download our thesis and access exclusive content.</p>
            <a href="{{ url('/download-thesis') }}" class="button">Download Thesis</a>
        @elseif($member->register_type === 2)
            <p>Thank you for choosing our Premium Membership!</p>
            <p><strong>Your payment is being processed.</strong> You'll receive another email once your payment is confirmed and your premium benefits are activated.</p>
        @endif
        
        <p>If you have any questions, feel free to reach out to us at <a href="mailto:network@aden.africa">network@aden.africa</a></p>
        
        <p>Best regards,<br>
        The Aden Africa Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Aden Africa. All rights reserved.</p>
    </div>
</body>
</html>