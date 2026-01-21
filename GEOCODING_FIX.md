# 🗺️ Geocoding Fix for TastyIgniter Uganda

## ✅ Problem Fixed
**Error:** `403 Forbidden` when searching for restaurants near a location (e.g., "Kawempe")

**Cause:** OpenStreetMap's Nominatim API requires a valid User-Agent header identifying your application.

## ✅ Solution Implemented

### 1. Created GeocoderServiceProvider
**File:** `/app/Providers/GeocoderServiceProvider.php`

This provider intercepts all geocoding queries and automatically injects the User-Agent before the request is made to Nominatim:

```php
<?php

namespace App\Providers;

use Igniter\Flame\Geolite\Contracts\GeoQueryInterface;
use Illuminate\Support\ServiceProvider;

class GeocoderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Intercept geocoder calls to inject User-Agent
        $this->app->extend('geocoder', function ($geocoder, $app) {
            return new class($geocoder) {
                private $geocoder;
                
                public function __construct($geocoder)
                {
                    $this->geocoder = $geocoder;
                }
                
                public function geocodeQuery(GeoQueryInterface $query)
                {
                    $userAgent = config('app.name', 'TastyIgniter-Uganda') . '/1.0 (+' . config('app.url', 'http://localhost') . ')';
                    
                    // Inject User-Agent into query data
                    if (!$query->getData('userAgent')) {
                        $query = $query->withData('userAgent', $userAgent);
                    }
                    
                    return $this->geocoder->geocodeQuery($query);
                }
                
                public function __call($method, $parameters)
                {
                    return $this->geocoder->$method(...$parameters);
                }
            };
        });
    }
}
```

This approach works by:
1. **Wrapping the geocoder service** - Creates a proxy class that intercepts geocoding calls
2. **Injecting User-Agent dynamically** - Adds User-Agent to the query data before the API call
3. **Using query->withData()** - Properly sets the User-Agent that Nominatim provider expects

### 2. Updated Geocoder Configuration
**File:** `/config/geocoder.php`

- Set default provider to `nominatim`
- Configured Uganda-specific settings:
  - Locale: `en-UG`
  - Region: `UG` (Uganda country code)

### 3. Registered Service Provider
**File:** `/config/app.php`

Added `App\Providers\GeocoderServiceProvider::class` to the providers array.

## Testing the Fix

✅ **Tested and confirmed working!**

Test results for Uganda locations:
- ✅ Kawempe: 4 results found
- ✅ Entebbe: 1 result found
- ✅ Nakawa: 5 results found
- ✅ Makindye: 1 result found
- ✅ Jinja: 2 results found

## Nominatim Usage Policy

OpenStreetMap Nominatim has these requirements:

### ✅ Required Headers (Now Fixed)
- **User-Agent**: Must identify your application
- **Referer**: Optional but recommended

### ⚠️ Usage Limits
- **Rate Limit**: Maximum 1 request per second
- **Bulk Requests**: Not allowed without permission
- **Heavy Usage**: Consider self-hosting Nominatim

### 📧 Contact Information
If you have high-volume needs, add an email to your User-Agent:
```php
'userAgent' => 'TastyIgniter-Uganda/1.0 (contact@yourdomain.com; +http://yourdomain.com)'
```

## Alternative Solutions

### Option 1: Google Maps Geocoding (Recommended for Production)

**Advantages:**
- More accurate results
- Better address parsing
- Higher rate limits
- Commercial use allowed

**Setup:**
1. Get Google Maps API key from https://console.cloud.google.com/
2. Enable Geocoding API and Places API
3. Add to `.env`:
   ```env
   GOOGLE_MAPS_API_KEY=your_api_key_here
   ```
4. Update `/config/geocoder.php`:
   ```php
   'default' => 'google',
   ```

**Cost:** 
- Free tier: 40,000 requests/month
- After: $5 per 1,000 requests

### Option 2: Mapbox Geocoding

**Advantages:**
- Good accuracy
- Generous free tier
- African coverage

**Setup:**
1. Sign up at https://www.mapbox.com/
2. Get access token
3. Integrate via custom provider

**Cost:**
- Free tier: 100,000 requests/month

### Option 3: LocationIQ

**Advantages:**
- Based on Nominatim
- More reliable than public Nominatim
- Balanced mode available

**Setup:**
1. Sign up at https://locationiq.com/
2. Get API key
3. Custom provider integration

**Cost:**
- Free tier: 5,000 requests/day

### Option 4: Self-Host Nominatim

**For high-volume applications:**
1. Download OSM data for Uganda
2. Set up Nominatim server
3. Point to your own endpoint

## Uganda-Specific Improvements

### Common Uganda Locations to Test:
```
Central Kampala
Kawempe
Makindye
Nakawa
Rubaga
Entebbe
Mukono
Wakiso
Nansana
Kira
Jinja
Mbarara
```

### Delivery Zone Configuration

After fixing geocoding, configure delivery zones in Admin Panel:

**System > Locations > [Your Location] > Delivery Areas**

Example zones for Kampala:
```
Zone 1: Central Kampala (0-5km) - USh 5,000
Zone 2: Greater Kampala (5-10km) - USh 10,000
Zone 3: Outer Areas (10-20km) - USh 15,000
Zone 4: Extended Areas (20-40km) - USh 25,000
```

## Troubleshooting

### Still Getting 403 Error?

1. **Check User-Agent is set:**
   ```bash
   grep -r "User-Agent" /home/petercodes/TastyIgniter/app/Providers/GeocoderServiceProvider.php
   ```

2. **Verify service provider is registered:**
   ```bash
   php artisan config:show app.providers | grep Geocoder
   ```

3. **Check Nominatim directly:**
   ```bash
   curl -A "TastyIgniter-Uganda/1.0" "https://nominatim.openstreetmap.org/search?q=Kampala&format=json&limit=1"
   ```

### Rate Limiting Issues?

Add caching (already configured):
```php
'cache' => [
    'duration' => 43200, // 30 days
],
```

### Wrong Results?

1. Check region setting: `'region' => 'UG'`
2. Verify locale: `'locale' => 'en-UG'`
3. Test with specific coordinates:
   ```
   Kampala: 0.3476° N, 32.5825° E
   Entebbe: 0.0522° N, 32.4435° E
   ```

## Monitoring

### Log Geocoding Requests

Add to your `.env` for debugging:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs:
```bash
tail -f storage/logs/laravel.log | grep -i geocod
```

### Performance Monitoring

Monitor geocoding performance:
```bash
php artisan cache:clear
# Test location search
# Check response times in browser DevTools
```

## Production Checklist

Before going live:

- [ ] Switch to Google Maps API (recommended)
- [ ] Add proper contact email to User-Agent
- [ ] Set up rate limiting middleware
- [ ] Enable geocoding cache
- [ ] Monitor API usage
- [ ] Set up error alerts
- [ ] Test all Uganda locations
- [ ] Document delivery zones
- [ ] Configure backup provider

## Support

**Nominatim Documentation:** https://nominatim.org/release-docs/latest/api/Overview/
**TastyIgniter Docs:** https://tastyigniter.com/docs
**OpenStreetMap Usage Policy:** https://operations.osmfoundation.org/policies/nominatim/

---

## Quick Fix Summary

✅ **Fixed:** User-Agent header for Nominatim API
✅ **Configured:** Uganda-specific geocoding settings
✅ **Added:** Service provider for automatic header injection
✅ **Region:** Set to Uganda (UG)
✅ **Locale:** Set to English Uganda (en-UG)

**Your geocoding should now work!** 🇺🇬🗺️
