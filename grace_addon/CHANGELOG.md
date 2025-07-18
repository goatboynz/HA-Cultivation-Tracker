# Changelog

All notable changes to the MediFlower Cultivation Tracker addon will be documented in this file.

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