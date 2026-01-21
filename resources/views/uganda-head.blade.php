<!-- Uganda Custom Enhancements -->
<link rel="stylesheet" href="/themes/uganda-custom.css">
<script src="/themes/uganda-enhancements.js" defer></script>

<style>
    /* Quick inline enhancements */
    body {
        font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif;
    }
    
    /* Uganda Pride Banner */
    .site-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, 
            #000000 0%, #000000 16.66%,
            #FCDC04 16.66%, #FCDC04 33.33%,
            #D90000 33.33%, #D90000 50%,
            #000000 50%, #000000 66.66%,
            #FCDC04 66.66%, #FCDC04 83.33%,
            #D90000 83.33%, #D90000 100%
        );
        z-index: 9999;
    }
    
    /* Enhanced logo area */
    .navbar-brand {
        position: relative;
        padding-left: 50px;
    }
    
    .navbar-brand::before {
        content: '🇺🇬';
        position: absolute;
        left: 0;
        font-size: 2rem;
        top: 50%;
        transform: translateY(-50%);
    }
</style>
