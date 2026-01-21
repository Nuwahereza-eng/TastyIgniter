# Uganda Configuration Guide for TastyIgniter

## 🇺🇬 Uganda-Specific Settings Applied

### System Configuration
- **Timezone**: Africa/Kampala (EAT - UTC+3)
- **Currency**: UGX (Ugandan Shilling)
- **Locale**: en_UG

### Admin Panel Configuration

Access admin panel at: **http://localhost:8080/admin**

#### 1. General Settings (System > Settings > General)
```
Site Name: [Your Restaurant Name]
Site URL: http://localhost:8080 (or your domain)
Default Country: Uganda
Default City: Kampala
```

#### 2. Currency Settings (System > Settings > Localization)
```
Currency: UGX
Currency Symbol: USh or UGX
Currency Format: USh {price}
Currency Decimals: 0 (Ugandan Shillings don't use decimals)
Thousands Separator: ,
Decimal Separator: .

Example: USh 50,000
```

#### 3. Tax Settings (Sales > Taxes)
```
Tax Name: VAT
Tax Rate: 18%
Tax Type: Percentage
Apply to: All Products
```

#### 4. Phone Number Format
```
Country Code: +256
Format: +256 XXX XXX XXX

Valid formats:
- +256712345678
- 0712345678
```

#### 5. Payment Gateways (Sales > Payments)

**Enable and Configure**:
1. ✅ Mobile Money (MTN & Airtel)
2. ✅ Flutterwave
3. ✅ Cash on Delivery (optional)

#### 6. Delivery Settings (Sales > Delivery)
```
Default Delivery Area: Kampala
Delivery Zones:
- Central Kampala: 0 - 5km (USh 5,000)
- Greater Kampala: 5 - 15km (USh 10,000)
- Entebbe Road: 15 - 40km (USh 20,000)

Minimum Order: USh 10,000
Free Delivery Above: USh 50,000 (optional)
```

#### 7. Operating Hours
```
Timezone: Africa/Kampala
Opening Time: 08:00 AM
Closing Time: 10:00 PM
Delivery Time: 30-60 minutes

Days: Monday - Sunday
```

#### 8. Location Settings (System > Locations)
```
Location Name: Kampala Branch
Address: [Your Address], Kampala, Uganda
Phone: +256 XXX XXX XXX
Email: info@yourrestaurant.com
```

### Payment Gateway Setup

#### MTN Mobile Money
1. Sign up: https://momodeveloper.mtn.com/
2. Get API credentials
3. Configure in: Sales > Payments > Mobile Money
4. Enable for Uganda customers

#### Airtel Money
1. Register: https://developers.airtel.africa/
2. Get API keys
3. Add to Mobile Money payment settings

#### Flutterwave
1. Create account: https://flutterwave.com/ug
2. Get: Public Key, Secret Key, Encryption Key
3. Configure in: Sales > Payments > Flutterwave
4. Enable: Card, Mobile Money Uganda, USSD

### Sample Menu Pricing (in UGX)

```
Appetizers: 5,000 - 15,000
Main Courses: 15,000 - 50,000
Drinks: 2,000 - 10,000
Desserts: 5,000 - 15,000

Delivery: 5,000 - 20,000 (based on distance)
```

### Email Configuration (System > Settings > Mail)

**Recommended for Uganda**:

1. **Using Gmail**:
```
Mail Driver: SMTP
Host: smtp.gmail.com
Port: 587
Username: your-email@gmail.com
Password: your-app-password
Encryption: TLS
```

2. **Using Mailgun**:
```
Mail Driver: Mailgun
Domain: mg.yourdomain.com
Secret: your-mailgun-api-key
```

### Mobile Money Integration

#### MTN Mobile Money API
- Base URL (Sandbox): https://sandbox.momodeveloper.mtn.com
- Base URL (Live): https://proxy.momoapi.mtn.com
- Product: Collection API
- Currency: UGX

#### Airtel Money API
- Base URL (UAT): https://openapiuat.airtel.africa
- Base URL (Live): https://openapi.airtel.africa
- Country: Uganda (UG)
- Currency: UGX

### Recommended Extensions

Install from TastyIgniter marketplace:
- ✅ Google Maps (for delivery tracking)
- ✅ SMS Notifications (for order updates)
- ✅ WhatsApp Integration
- ✅ Multi-language (add Luganda if needed)

### Uganda Business Requirements

1. **Tax Compliance**:
   - VAT Rate: 18%
   - Get TIN (Tax Identification Number)
   - Issue proper receipts/invoices

2. **Food Safety**:
   - UNBS certification
   - Health inspection compliance

3. **Payment Licensing**:
   - Register with Bank of Uganda for payment processing
   - Comply with mobile money regulations

### Testing Checklist

- [ ] Timezone shows correct Kampala time
- [ ] Currency displays as UGX/USh
- [ ] Phone numbers accept +256 format
- [ ] VAT calculated at 18%
- [ ] Mobile Money payment works
- [ ] Flutterwave payment works
- [ ] Delivery zones configured
- [ ] Email notifications working
- [ ] SMS notifications (optional)

### Production Deployment

1. **Environment**:
   - Change APP_ENV to "production"
   - Set APP_DEBUG to false
   - Generate new APP_KEY

2. **Security**:
   - Enable HTTPS/SSL
   - Use strong passwords
   - Backup database regularly

3. **Payment Gateways**:
   - Switch to live API keys
   - Disable test mode
   - Configure webhooks

4. **Domain Setup**:
   - Update APP_URL in .env
   - Configure DNS records
   - Set up email domain

### Support Resources

- TastyIgniter Docs: https://tastyigniter.com/docs
- MTN MoMo: https://momodeveloper.mtn.com
- Airtel Money: https://developers.airtel.africa
- Flutterwave: https://developer.flutterwave.com

### Common Ugandan Telecom Prefixes

```
MTN: 077, 078, 039, 076
Airtel: 070, 075, 020
Uganda Telecom: 071
```

---

## Quick Start Commands

```bash
# Start development server
php artisan serve --host=0.0.0.0 --port=8080

# Clear cache
php artisan cache:clear

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed
```

## Access URLs

- Frontend: http://localhost:8080
- Admin Panel: http://localhost:8080/admin
- API: http://localhost:8080/api

---

**Note**: Replace all placeholder values with your actual information before going live.
