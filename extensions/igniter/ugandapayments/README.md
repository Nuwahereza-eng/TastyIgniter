# Uganda Payment Methods Extension for TastyIgniter

This extension adds Uganda-specific payment gateways to your TastyIgniter installation.

## Payment Methods Included

### 1. Mobile Money (MTN & Airtel)
- MTN Mobile Money
- Airtel Money
- Direct mobile money payments for Ugandan customers

### 2. Flutterwave
- Credit/Debit Cards
- Mobile Money Uganda
- USSD Payments
- Bank Transfers
- Multi-currency support (UGX, USD)

## Installation

1. The extension is already installed in `extensions/igniter/ugandapayments/`
2. Enable the extension from the admin panel: **System > Extensions**
3. Configure each payment gateway

## Configuration

### Mobile Money Setup

1. **MTN Mobile Money**:
   - Sign up at: https://momodeveloper.mtn.com/
   - Get API credentials (API Key, API Secret)
   - Subscribe to Collection API product
   
2. **Airtel Money**:
   - Register at: https://developers.airtel.africa/
   - Get API credentials
   - Complete KYC verification

3. **In Admin Panel**:
   - Go to **Sales > Payments**
   - Click on **Mobile Money**
   - Enter your API credentials
   - Set minimum order total (default: 1000 UGX)
   - Enable test mode for testing

### Flutterwave Setup

1. **Get API Keys**:
   - Sign up at: https://flutterwave.com/ug
   - Go to Settings > API Keys
   - Copy: Public Key, Secret Key, Encryption Key

2. **In Admin Panel**:
   - Go to **Sales > Payments**
   - Click on **Flutterwave**
   - Enter your API credentials
   - Select accepted payment methods:
     - ✓ Credit/Debit Card
     - ✓ Mobile Money Uganda (recommended)
     - ✓ USSD
     - ✓ Bank Transfer
   - Set currency: UGX (Ugandan Shilling)
   - Enable test mode for testing

## Testing

### Test Mode
- Enable "Test Mode" in payment settings
- Use test API keys provided by the payment providers
- Test with test phone numbers and cards

### MTN Test Credentials
- Test Number: 256772123456
- See: https://momodeveloper.mtn.com/api-documentation/testing/

### Flutterwave Test Cards
- Card: 5531886652142950
- CVV: 564
- Expiry: 09/32
- PIN: 3310
- OTP: 12345

## Uganda-Specific Features

✅ **Currency**: Pre-configured for UGX (Ugandan Shilling)
✅ **Timezone**: Africa/Kampala (EAT - UTC+3)
✅ **Phone Format**: +256 XXX XXX XXX validation
✅ **VAT**: Configure 18% tax rate in admin settings
✅ **Local Payment Methods**: MTN, Airtel, Flutterwave

## API Documentation

- **MTN MoMo**: https://momodeveloper.mtn.com/docs/services/collection/
- **Airtel Money**: https://developers.airtel.africa/documentation
- **Flutterwave**: https://developer.flutterwave.com/docs

## Support

For issues or questions about:
- MTN Mobile Money: support@momodeveloper.mtn.com
- Airtel Money: api@airtel.africa
- Flutterwave: developers@flutterwavego.com

## Security Notes

⚠️ **Important**:
- Never commit API keys to version control
- Use environment variables for production
- Enable test mode during development
- Verify webhook signatures
- Use HTTPS in production

## Webhook Setup (Flutterwave)

Add this webhook URL in your Flutterwave dashboard:
```
https://yourdomain.com/flutterwave/webhook
```

## Production Checklist

- [ ] Disable test mode
- [ ] Use live API keys
- [ ] Configure webhook URLs
- [ ] Enable HTTPS/SSL
- [ ] Set correct currency (UGX)
- [ ] Test with real transactions
- [ ] Set appropriate order statuses
- [ ] Configure email notifications

## License

Custom extension for TastyIgniter
