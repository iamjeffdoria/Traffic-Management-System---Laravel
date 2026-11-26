# Kill leftover processes that lock NativePHP's PHP binary
taskkill /F /IM php.exe /T 2>$null
taskkill /F /IM electron.exe /T 2>$null

# Give Windows a moment to release the file handles
Start-Sleep -Seconds 2

php artisan native:run