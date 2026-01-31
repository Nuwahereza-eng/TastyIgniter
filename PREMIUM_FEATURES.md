# UgaEats Premium Features

This document describes the advanced features implemented for UgaEats food delivery platform.

## Features Overview

### 1. Group Orders 🍕👥
**Location:** `/account/features#group-orders`

Group ordering allows multiple users to collaborate on a single order:

**Features:**
- Create a group order with a unique invite code
- Share the code via WhatsApp or copy/paste
- Each participant adds their own items
- Multiple split options:
  - **Equal Split** - Divide total equally among participants
  - **Pay for Own Items** - Each person pays for what they ordered
  - **Host Pays All** - Group organizer covers everything
- Order deadline enforcement
- Real-time participant tracking

**How it works:**
1. User creates a group order and selects a restaurant
2. A unique 8-character code is generated (e.g., `ABCD-1234`)
3. Code is shared with friends via WhatsApp or other means
4. Each participant joins using the code and adds items
5. When deadline arrives, order is finalized and submitted

### 2. Scheduled Orders ⏰📅
**Location:** `/account/features#scheduled-orders`

Schedule orders for future delivery:

**Features:**
- Select delivery date: Today, Tomorrow, or Custom date
- Choose from available time slots (30-minute windows)
- Recurring order option with frequencies:
  - Daily
  - Weekly
  - Bi-weekly
  - Monthly
- Visual confirmation of scheduled date/time
- Direct link to restaurant menu

**Use Cases:**
- Office lunch pre-orders
- Birthday/party catering
- Weekly meal prep
- Daily breakfast delivery

### 3. Meal Subscription Plans 🍽️📆
**Location:** `/account/features#subscriptions`

Subscribe to regular meal deliveries and save:

**Plans Available:**

| Plan | Price/Week | Meals | Features |
|------|------------|-------|----------|
| **Basic** | UGX 150,000 | 5 | Free delivery, Standard restaurants |
| **Premium** | UGX 280,000 | 10 | Express delivery, All restaurants, Priority support |
| **Family** | UGX 450,000 | 21 | Express delivery, All restaurants, Family sharing (4), VIP support |

**Features:**
- Automatic weekly/monthly billing
- Preferred delivery time selection
- Default delivery address
- Cancel anytime
- Up to 20% savings vs individual orders

### 4. Order Tracking 🏍️📍
**Public Location:** `/track-order` (no login required)
**Account Location:** `/account/features#order-tracking`

Real-time order tracking with:

**Features:**
- Visual order status timeline:
  1. Order Placed ✓
  2. Restaurant Confirmed ✓
  3. Preparing Food ✓
  4. Out for Delivery 🔄
  5. Delivered ✓
- Animated route visualization
- ETA countdown
- Rider information:
  - Name and photo
  - Rating and delivery count
  - Vehicle details
  - Direct call/WhatsApp buttons
- Order summary (restaurant, items, total, address)

## Files Created

### Pages
- `vendor/tastyigniter/ti-theme-orange/resources/views/_pages/account/features.blade.php`
  - Main features dashboard with all 4 features in tabs
  - Requires customer login
  
- `vendor/tastyigniter/ti-theme-orange/resources/views/_pages/track-order.blade.php`
  - Public order tracking page
  - No login required

### CSS
- `public/themes/demo/assets/css/features.css`
  - Styles for all feature components
  - Responsive design
  - Animations and visual effects

### Patches (for deployment persistence)
- `patches/features.blade.php`
- `patches/track-order.blade.php`
- `patches/features.css`

## Accessing Features

### For Logged-in Users
Navigate to: **Account → Premium Features** or visit `/account/features`

### For Public Order Tracking
Visit: `/track-order` and enter order number

## Technical Notes

### Data Storage
Features use both API backend and `localStorage` fallback:
- Backend API at `/api/group-orders`, `/api/scheduled-orders`, `/api/subscriptions`, `/api/tracking`
- `localStorage` used as fallback when API is unavailable

### Backend Implementation

#### Database Tables (Migration: `2026_01_26_000001_create_premium_features_tables.php`)

| Table | Description |
|-------|-------------|
| `group_orders` | Group order sessions with host, deadline, split method |
| `group_order_participants` | Participants in group orders with their items |
| `scheduled_orders` | Scheduled/recurring order configurations |
| `subscription_plans` | Available subscription tiers (Basic, Premium, Family) |
| `subscriptions` | Customer subscriptions with meals remaining, expiry |
| `order_tracking` | Real-time tracking with rider info and location |

#### Models (in `app/Models/`)
- `GroupOrder.php` - Group order management with invite codes
- `GroupOrderParticipant.php` - Participant tracking and item management
- `ScheduledOrder.php` - Scheduled and recurring order handling
- `SubscriptionPlan.php` - Plan definitions with features
- `Subscription.php` - Customer subscription management
- `OrderTracking.php` - Order tracking with status progression

#### API Controllers (in `app/Http/Controllers/Api/`)
- `GroupOrderController.php` - Create, join, finalize group orders
- `ScheduledOrderController.php` - Schedule orders with recurring support
- `SubscriptionController.php` - Subscribe, pause, cancel subscriptions
- `OrderTrackingController.php` - Track orders, update rider location

#### API Endpoints (in `routes/api.php`)

**Group Orders:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/group-orders` | List user's group orders |
| POST | `/api/group-orders` | Create new group order |
| POST | `/api/group-orders/join` | Join via invite code |
| PUT | `/api/group-orders/{id}/items` | Update participant items |
| POST | `/api/group-orders/{id}/finalize` | Finalize group order |
| POST | `/api/group-orders/{id}/cancel` | Cancel group order |

**Scheduled Orders:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/scheduled-orders` | List scheduled orders |
| POST | `/api/scheduled-orders` | Create scheduled order |
| GET | `/api/scheduled-orders/time-slots` | Get available time slots |
| PUT | `/api/scheduled-orders/{id}` | Update scheduled order |
| POST | `/api/scheduled-orders/{id}/cancel` | Cancel scheduled order |
| POST | `/api/scheduled-orders/{id}/skip-next` | Skip next occurrence |

**Subscriptions:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/subscriptions/plans` | List available plans |
| GET | `/api/subscriptions/current` | Get current subscription |
| POST | `/api/subscriptions/subscribe` | Subscribe to plan |
| POST | `/api/subscriptions/use-meal` | Use a meal from subscription |
| POST | `/api/subscriptions/pause` | Pause subscription |
| POST | `/api/subscriptions/resume` | Resume subscription |
| POST | `/api/subscriptions/cancel` | Cancel subscription |

**Order Tracking:**
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/tracking/track` | Track order by ID (public) |
| GET | `/api/tracking/status/{orderId}` | Get tracking status |
| GET | `/api/tracking/statuses` | Get all status labels |
| PUT | `/api/tracking/{orderId}/status` | Update order status (admin) |
| POST | `/api/tracking/{orderId}/assign-rider` | Assign rider (admin) |
| PUT | `/api/tracking/{orderId}/location` | Update rider location |
| POST | `/api/tracking/{orderId}/delivered` | Mark as delivered |

### Running Migrations
```bash
php artisan migrate
```

### Seeding Subscription Plans
```bash
php artisan db:seed --class=SubscriptionPlanSeeder
```

## Deployment

After running `composer update`, execute:
```bash
./apply-patches.sh
```

This will copy the feature pages from `patches/` to the theme directory.

## URLs Summary

| Feature | URL | Auth Required |
|---------|-----|---------------|
| Features Dashboard | `/account/features` | Yes |
| Group Orders Tab | `/account/features#group-orders` | Yes |
| Scheduled Orders Tab | `/account/features#scheduled-orders` | Yes |
| Subscriptions Tab | `/account/features#subscriptions` | Yes |
| Order Tracking Tab | `/account/features#order-tracking` | Yes |
| Public Order Tracking | `/track-order` | No |

---

*Last Updated: January 2026*
*UgaEats - Bringing Uganda's Flavors to Your Door* 🇺🇬
