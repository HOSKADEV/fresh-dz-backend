<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <script>
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;

        if (/android/i.test(userAgent)) {
            window.location.href = "https://play.google.com/store/apps/details?id=com.fresh.dz&hl=en";
        } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            window.location.href = "https://apps.apple.com/dz/app/fresh-dz-%D9%81%D8%B1%D8%A7%D8%B4-%D8%AF%D9%8A%D8%B2%D8%A7%D8%AF/id6744602129?l=ar";
        } else {
            window.location.href = "{{ url('/') }}"; // رابط افتراضي
        }
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
