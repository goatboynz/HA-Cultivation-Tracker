# Changelog

All notable changes to the MediFlower Cultivation Tracker addon will be documented in this file.

## [2.7.0] - 2025-01-19

### 💾 **Database Management System**
- **Complete Backup & Restore**: Professional-grade database backup and restore functionality
- **Database Information Display**: Real-time stats showing file size, record counts, and table details
- **Safety Features**: Multiple confirmation dialogs and automatic backup before restore
- **File Validation**: Ensures uploaded files are valid SQLite databases with size limits
- **Database Health Check**: Validation tools to ensure database integrity

### 🔍 **Enhanced Harvest Management**
- **Comprehensive Plant Filtering**: Filter by stage, room, status, genetics, age, and mother plants
- **Complete Plant Display**: Shows ALL plants (not just growing) with full details
- **Advanced Table View**: Tracking numbers, tags, genetics, stages, rooms, status, age, and type
- **Real-time Filter Feedback**: Live count of filtered results and selected plants
- **Smart Selection Tools**: Select all with filtered results, clear filters button
- **Mother Plant Identification**: Visual indicators for mother plants vs regular plants

### ✨ **User Experience Improvements**
- **Modern Filter Interface**: Card-based layout with responsive grid design
- **Visual Status Indicators**: Color-coded badges for plant status and growth stages
- **Enhanced Feedback**: Real-time selection counters and filter result displays
- **Better Confirmations**: Detailed confirmation dialogs for destructive operations
- **Auto-refresh**: Automatic data reload after successful operations

### 🎨 **Visual Enhancements**
- **Status Badge System**: Color-coded Growing (green), Harvested (orange), Destroyed (red), Sent (blue)
- **Stage Indicators**: Visual badges for Clone, Veg, Flower, Mother stages
- **Mother Plant Icons**: Crown emoji (👑) for easy mother plant identification
- **Responsive Design**: Mobile-friendly layouts and touch-optimized controls

### 🔧 **Technical Improvements**
- **Enhanced Backend API**: Updated plant data endpoints with comprehensive information
- **Dynamic Filter Population**: Automatically populated filters from actual plant data
- **Performance Optimization**: Efficient filtering and display update mechanisms
- **Better Error Handling**: Comprehensive validation and user-friendly error messages

## [2.6.0] - 2025-01-19

### 🎯 Enhanced Navigation & Dashboard
- **Expanded Dashboard Quick Actions**: Added Record Weights, Manage Rooms, and Seed Stock management
- **Enhanced Navigation Bar**: Added Operations dropdown with weight recording, shipping, and dried flower management
- **Improved Plant Management**: Added Take Clones and Harvest Plants to main navigation
- **Better Organization**: Streamlined navigation with logical groupings and emoji icons

### 🔧 Critical Bug Fixes
- **Fixed Veg to Flower Movement**: Resolved 500 error when moving plants from vegetative to flowering stage
- **Updated POST Requests**: Fixed all plant movement operations to use proper POST requests instead of GET

### 🎨 Modern Theme Expansion
- **Harvest Plants Page**: Complete modern theme makeover with cards, enhanced layout, and better functionality
- **Take Clones Page**: Modern design with improved form layout and user experience
- **Record Dry Weight Page**: Modern cards and responsive grid layout for weight transactions
- **Seed Stock Management**: Enhanced with modern theme consistency

### ✨ New Features
- **Comprehensive Plant Summary**: New detailed plant view with timeline, photos, and harvest information
- **Enhanced Quick Actions**: 8 organized quick action buttons on dashboard for common tasks
- **Better Accessibility**: All major functionality now easily accessible from dashboard and navigation
- **Improved User Flow**: Logical navigation paths between related functions

### 🚀 Technical Improvements
- **Consistent Styling**: Modern theme applied across all key management pages
- **Enhanced Forms**: Better form layouts with responsive grids and modern inputs
- **Improved Error Handling**: Better user feedback and status messages
- **Mobile Responsive**: All updated pages work seamlessly on mobile devices

## [2.5.0] - 2025-01-19

### 🔧 Critical Bug Fixes
- **Fixed Move Plant Functionality**: Resolved 500 error when moving plants between stages
  - Root cause: GET requests were being sent to POST-only endpoint
  - Solution: Updated `quickMove()` function to use proper POST requests with FormData
  - Enhanced `move_plants.php` to handle both individual and bulk plant moves

### 🎨 Enhanced Modern Theme
- **Updated Plant Tracking Page**: Complete modern theme makeover with card-based layout
- **New Plant Summary Page**: Comprehensive plant details view with timeline and photos
- **Enhanced All Plants Page**: Added "View" button for detailed plant summary
- **Improved Edit Plant Page**: Fixed CSS references and enhanced functionality

### ✨ New Features
- **Plant Summary View**: Detailed plant information with timeline, photos, and harvest data
- **Enhanced Navigation**: Better organization of plant tracking features
- **Test Functionality**: Added debugging tools for move plant operations
- **Responsive Cards**: Modern card layouts with emoji icons throughout

### 🚀 Technical Improvements
- Fixed POST/GET request handling in plant movement system
- Enhanced error handling and user feedback
- Improved data formatting for plant operations
- Better mobile responsiveness across all pages

## [2.4.0] - 2025-01-18

### 🎨 Complete Modern Theme Implementation
- **All Pages Updated**: Applied modern dark theme to all plant, genetics, and room management pages
- **Consistent Design**: Professional dark theme inspired by modern dashboard designs
- **Enhanced UX**: Modern cards, hover effects, and smooth animations throughout
- **Better Navigation**: Updated navigation with emoji icons and improved dropdowns
- **Responsive Design**: Mobile-friendly layouts that work on all devices

### ✨ New Features
- Modern card-based layouts for all management pages
- Enhanced action buttons with icons and better styling
- Improved form designs with modern input styling
- Better empty states with helpful messaging
- Consistent color scheme using CSS variables

### 🔧 Technical Improvements
- Simplified addon installation (removed complex Docker workflows)
- Better Home Assistant integration
- Improved responsive design
- Enhanced accessibility

## [2.1.0] - 2025-01-18

### 🎉 Major UI/UX Overhaul
- **Enhanced Navigation**: Modern design with icons and user-friendly dropdowns
- **Improved Dashboard**: Real-time statistics, progress bars, and enhanced quick actions
- **Company Management**: Added ability to view, edit, and delete verified companies
- **Enhanced Reports**: Plant count exports for monthly, 6-month, and yearly periods

### ✨ New Features
- Modern navigation with emoji icons and gradients
- Dashboard with real-time plant statistics and progress indicators
- Company management page with inline editing
- Plant count export reports (CSV/Excel)
- Enhanced quick actions with 8 action buttons
- Room utilization overview with visual indicators
- Plants ready for harvest tracking with status colors
- Genetics overview showing active plant counts
- Auto-refresh dashboard every 5 minutes

### 🔧 Fixes
- Fixed Mother Plants room loading issue
- Removed dummy data system (no longer needed)
- Removed theme toggle as requested
- Fixed container startup permission issues
- Resolved service conflicts and duplicate processes

### 🎨 Visual Improvements
- Modern CSS styling with gradients and animations
- Responsive design that works on all devices
- Better typography and color schemes
- Hover effects and smooth transitions
- Progress bars and status indicators

## [2.0.0] - 2025-01-17

### 🚀 Initial Release
- Complete cultivation management system
- Plant tracking through all growth stages
- Genetics database management
- Room management and plant movement
- Harvest tracking and reporting
- Mother plant management
- Clone tracking system
- Comprehensive reporting suite
- SQLite database backend
- Modern web interface

### 📊 Core Features
- Dashboard with plant overview
- Plant management (Clone, Veg, Flower, Mother stages)
- Genetics and seed stock management
- Room management and utilization
- Harvest recording and tracking
- Company and contact management
- Document management system
- Compliance reporting tools

### 🔒 Security & Compliance
- Authentication system
- Data validation and sanitization
- Audit trail capabilities
- Compliance reporting features
- Secure file handling