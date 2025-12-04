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
        .success-box {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Payment Successful!</h1>
    </div>
    
    <div class="content">
        <p>Hi {{ $member->name }},</p>
        
        <div class="success-box">
            <h2 style="margin: 0;">Your Premium Membership is Now Active!</h2>
        </div>
        
        <p>Your payment has been successfully processed. Welcome to our Premium community!</p>
        
        <div class="details">
            <h3>Payment Details</h3>
            <div class="detail-row">
                <span><strong>Transaction Reference:</strong></span>
                <span>{{ $transaction->referenceId }}</span>
            </div>
            <div class="detail-row">
                <span><strong>Amount Paid:</strong></span>
                <span>${{ number_format($transaction->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span><strong>Membership Type:</strong></span>
                <span>Premium Membership</span>
            </div>
            <div class="detail-row">
                <span><strong>Valid Until:</strong></span>
                <span>{{ now()->addYear()->format('F j, Y') }}</span>
            </div>
        </div>
        
        <h3>Your Premium Benefits:</h3>
        <ul>
            <li>Full access to all network resources</li>
            <li>Exclusive premium content and thesis downloads</li>
            <li>Priority support and assistance</li>
            <li>Access to premium events and webinars</li>
            <li>Networking with premium members</li>
        </ul>
        
        <p>You can now access all premium features on our platform.</p>
        
        <p>If you have any questions, contact us at <a href="mailto:network@aden.africa">network@aden.africa</a></p>
        
        <p>Best regards,<br>
        The Aden Africa Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} Aden Africa. All rights reserved.</p>
    </div>
</body>
</html>