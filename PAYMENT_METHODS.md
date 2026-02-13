# Payment Methods Implementation

This document describes the payment methods available for orders in TastyIgniter UgaEats.

## Available Payment Methods

### 1. Cash on Delivery (COD)
- **Code:** `cod`
- **Description:** Pay with cash when your order is delivered
- **Status:** Enabled by default

### 2. Mobile Money (MTN & Airtel)
- **Code:** `mobilemoney`
- **Description:** Pay using MTN Mobile Money or Airtel Money
- **Features:**
  - MTN Mobile Money integration
  - Airtel Money integration
  - Phone number validation (9 digits, +256 prefix)
  - Test mode available
- **Admin Configuration:**
  - MTN API Key & Secret
  - Airtel API Key & Secret
  - Test Mode toggle
  - Minimum Order Total
  - Order Status after payment

### 3. Flutterwave (Cards & Mobile Money)
- **Code:** `flutterwave`
- **Description:** Accept payments via Flutterwave payment gateway
- **Supported Methods:**
  - Credit/Debit Cards (Visa, Mastercard)
  - Mobile Money Uganda (MTN, Airtel)
- **Features:**
  - Secure payment processing via Flutterwave
  - Redirect-based payment flow
  - Payment verification
  - Test mode available
- **Admin Configuration:**
  - Public Key
  - Secret Key
  - Encryption Key
  - Test Mode toggle
  - Currency (default: UGX)

### 4. Tasty Wallet
- **Code:** `tastywallet`
- **Description:** Pay using your in-app Tasty Wallet balance
- **Features:**
  - Instant payment from wallet balance
  - 5% cashback on all wallet payments
  - Transaction history
  - Top-up via Mobile Money or Flutterwave
- **Admin Configuration:**
  - Minimum Order Total
  - Maximum Payment Amount
  - Cashback Percentage (default: 5%)
  - Order Status after payment

## How to Enable/Configure Payment Methods

1. Go to **Admin Panel** → **Marketing** → **Payments**
2. Click on the payment method you want to configure
3. Set the required fields and enable the payment method
4. Save changes

## Technical Details

### Extension Structure
```
extensions/igniter/ugandapayments/
├── Extension.php
├── composer.json
├── payments/
│   ├── MobileMoney.php
│   ├── Flutterwave.php
│   └── TastyWalletGateway.php
├── resources/
│   ├── models/
│   │   ├── mobilemoney.php
│   │   ├── flutterwave.php
│   │   └── tastywallet.php
│   └── views/
│       └── _partials/
│           ├── mobilemoney/
│           │   └── payment_form.blade.php
│           ├── flutterwave/
│           │   └── payment_form.blade.php
│           └── tastywallet/
│               └── payment_form.blade.php
```

### Wallet System

The Tasty Wallet system includes:

- **Database Tables:**
  - `tasty_wallets` - Customer wallets with balance
  - `wallet_transactions` - Transaction history

- **Models:**
  - `App\Models\TastyWallet` - Wallet model with credit/debit methods
  - `App\Models\WalletTransaction` - Transaction records

- **Wallet Page:** `/account/wallet`
  - View balance
  - Top up wallet (via Flutterwave)
  - View transaction history

### API Routes (Wallet)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/ajax/wallet/balance` | Get wallet balance |
| POST | `/ajax/wallet/topup` | Initiate wallet top-up |
| GET | `/ajax/wallet/transactions` | Get transaction history |
| GET | `/ajax/wallet/verify-topup` | Verify top-up payment |

## Testing

All payment gateways support test mode. When enabled:
- No actual payment processing occurs
- Orders are marked as paid immediately
- Demo transaction IDs are generated

To test:
1. Enable test mode in admin panel for each payment method
2. Place an order and select the payment method
3. Complete the payment form
4. Order will be marked as paid

## Troubleshooting

### Payment method not showing in checkout
1. Ensure the payment method is enabled (status = 1)
2. Check that minimum order total is met
3. For Tasty Wallet, ensure customer has sufficient balance

### Mobile Money payment failing
1. Verify API keys are correctly configured
2. Check that phone number format is correct (9 digits)
3. Review API logs for error details

### Flutterwave redirect not working
1. Verify all API keys are configured
2. Check redirect URL configuration
3. Ensure HTTPS is enabled for production
