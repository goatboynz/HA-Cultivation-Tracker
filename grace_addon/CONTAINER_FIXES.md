# Container Startup Fixes

## Issues Resolved
- Fixed permission denied errors on service startup scripts
- Corrected file paths between nginx configuration and application files
- Added proper initialization script for container startup
- Fixed PHP-FPM user/group configuration

## Changes Made

### 1. Added Container Initialization Script
- Created `/etc/cont-init.d/00-setup-permissions` to ensure all service files have execute permissions
- Sets up proper directory permissions for data, logs, and web files
- Handles database file permissions if it exists

### 2. Created Missing Service Files
- Added `php-fpm/run` and `php-fpm/finish` service scripts
- Added `nginx/finish` service script
- All service scripts now have proper error handling and logging

### 3. Fixed Configuration Issues
- Updated nginx.conf to point to correct web root (`/var/www/public`)
- Changed PHP-FPM to run as `php:nginx` instead of `root:root`
- Ensured proper file copying from `/www/public` to `/var/www/public`

### 4. Enhanced Dockerfile
- Added comprehensive permission setup during build
- Ensured all init and service scripts are executable
- Proper directory creation and ownership setup
- Database file permission handling

## Files Modified
- `Dockerfile` - Enhanced with permission fixes and proper file handling
- `files/general/etc/cont-init.d/00-setup-permissions` - New initialization script
- `files/general/etc/services.d/php-fpm/run` - New PHP-FPM service script
- `files/general/etc/services.d/php-fpm/finish` - New PHP-FPM finish script
- `files/general/etc/services.d/nginx/finish` - New nginx finish script
- `files/general/etc/nginx/nginx.conf` - Fixed web root path
- `files/php83/etc/php83/php-fpm.conf` - Fixed user/group configuration

These fixes should resolve the container startup issues and ensure the Grace addon runs properly in Home Assistant.