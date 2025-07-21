# Changelog

All notable changes to the MediFlower Cultivation Tracker addon will be documented in this file.

## [2.12.0] - 2025-01-19

### 🔄 **Multi-Select Batch Operations**
- **Clone Stage Management**: Select multiple clones with checkboxes for batch operations
- **Batch Move to Veg**: Move multiple clones to vegetative stage simultaneously
- **Batch Destruction**: Destroy multiple selected plants with single confirmation
- **Vegetative Stage Operations**: Select multiple veg plants for batch processing
- **Batch Move to Flower**: Move multiple veg plants to flowering stage
- **Batch Mother Conversion**: Convert multiple veg plants to mother plants
- **Visual Selection Interface**: Real-time selection counter and smart batch operation panel
- **Select All/Clear**: Quick selection tools for efficient plant management

### 🗑️ **Plant Deletion System**
- **Delete Plant Button**: Added prominent delete button to Edit Plant page
- **Double Confirmation**: Safety system with two confirmation dialogs
- **Related Data Cleanup**: Automatically removes photos, batch records, and updates child plants
- **Audit Trail**: Creates PlantDeletionLog for compliance and tracking
- **Transaction Safety**: Database transactions ensure complete deletion or rollback
- **Referential Integrity**: Properly handles mother-child plant relationships

### 📸 **Enhanced Photo Management**
- **Fixed Photo Uploads**: Resolved photo saving issues in edit plant form
- **File Upload Support**: Multiple photo selection and upload functionality
- **Camera Integration**: Take photos directly in browser with preview
- **Photo Preview System**: View and remove photos before saving
- **Current Photo Display**: Shows existing plant photos with date stamps
- **Proper File Handling**: Photos saved to uploads/plants/ with database records
- **Mobile Camera Support**: Works on mobile devices with camera access

### 🎨 **User Interface Improvements**
- **Batch Operations Panel**: Appears dynamically when plants are selected
- **Selection Feedback**: Real-time counter showing number of selected plants
- **Professional Styling**: Consistent modern design across all batch operations
- **Mobile Responsive**: All batch operations work perfectly on mobile devices
- **Visual Indicators**: Color-coded buttons and status messages for better UX

### 🔧 **Technical Enhancements**
- **Enhanced JavaScript**: Improved form handling and batch operation logic
- **Better Error Handling**: Comprehensive error messages and user feedback
- **Database Optimization**: Efficient batch processing with proper transactions
- **API Improvements**: Enhanced endpoints for batch operations and photo handling
- **Security Enhancements**: Proper validation and sanitization for all operations

## [2.11.0] - 2025-01-19

### 🔢 **Advanced Tracking Number System**
- **Mother-Based Tracking**: Tracking numbers now start with mother plant ID (e.g., 5-01, 5-02, 5-03)
- **Sequential Numbering**: Automatic sequential numbering for clones from each mother plant
- **Multi-Mother Support**: Distribute clones between multiple mother plants with proper tracking
- **Fallback System**: Standard CT-YYYY-XXXXXX format for non-clone plants

### 👥 **Multi-Mother Clone Selection**
- **Multiple Mother Selection**: Select multiple mother plants when adding clones
- **Clone Distribution**: Specify how many clones to take from each mother plant
- **Real-time Validation**: Live validation that clone distribution equals total plant count
- **Visual Feedback**: Color-coded status indicators for distribution accuracy
- **Dynamic Interface**: Add/remove mother plant selections as needed

### 📅 **Enhanced Date Management**
- **Custom Date Selection**: Choose the exact date when plants were added to the system
- **Default Current Time**: Automatically defaults to current date and time
- **Proper Date Handling**: Improved date formatting and storage in database
- **Timeline Accuracy**: Better tracking of when plants actually entered the system

### 🎨 **Improved User Interface**
- **Professional Multi-Select**: Modern interface for selecting multiple mothers
- **Distribution Status**: Real-time feedback on clone distribution
- **Enhanced Validation**: Better form validation with helpful error messages
- **Responsive Design**: Mobile-optimized multi-mother selection interface

### 🔧 **Technical Improvements**
- **Enhanced Tracking Generation**: New tracking number generation system with mother plant support
- **Improved Database Handling**: Better data validation and error handling
- **API Enhancements**: Updated mother plant API with debugging capabilities
- **Form Processing**: Enhanced form processing for multi-mother selections

## [2.10.0] - 2025-01-19

### 🎨 **Enhanced Edit Plant Page**
- **Complete UI Redesign**: Professional form layout with organized sections and modern styling
- **Comprehensive Field Coverage**: All plant attributes now editable including source information, dates, weights, and destruction details
- **Conditional Field Display**: Smart form that shows/hides relevant sections based on plant status and source type
- **Enhanced Photo Management**: Improved photo upload, camera capture, and photo gallery display
- **Better Form Validation**: Real-time validation with helpful error messages and field descriptions
- **Animated Transitions**: Smooth animations for conditional field visibility changes

### 🔧 **Fixed Mother Plant Selection**
- **Enhanced Mother Plant API**: Improved get_mother_plants.php to return complete plant information
- **Better Plant Filtering**: Fixed mother plant selection to show all available mother plants
- **Improved Data Loading**: Enhanced plant data loading with proper error handling
- **Test Utilities**: Added debugging tools to identify and resolve mother plant selection issues

### ✨ **Form Improvements**
- **Professional Styling**: Modern card-based layout with consistent spacing and typography
- **Field Organization**: Logical grouping of related fields with clear labels and descriptions
- **Enhanced UX**: Better visual hierarchy, hover effects, and interactive elements
- **Mobile Responsive**: Optimized form layout for all device sizes
- **Accessibility**: Improved form accessibility with proper labels and keyboard navigation

### 🚀 **Technical Enhancements**
- **Enhanced CSS Framework**: Extended modern theme with form-specific styles and animations
- **Improved JavaScript**: Better event handling, form validation, and user feedback
- **Photo Management**: Enhanced photo upload and camera functionality
- **Data Persistence**: Improved form data saving and error handling
- **API Improvements**: Enhanced plant data APIs with better error handling and data completeness

## [2.9.0] - 2025-01-19

### 🗑️ **Advanced Destruction Tracking System**
- **Destroyed Plants Page**: Complete tracking of all destroyed plants with mandatory reasons
- **Destruction Reasons**: Required selection from disease, pests, poor growth, hermaphrodite, overcrowding, quality control, compliance
- **Comprehensive Filtering**: Filter destroyed plants by reason, genetics, date range, and operation type
- **Witness Documentation**: Track witness names and compliance notes for destruction operations
- **Summary Statistics**: Total destroyed plants, monthly counts, weight tracking, and batch operation summaries

### 📦 **Professional Batch Operations System**
- **Batch Harvest Operations**: Group multiple plants for harvest with individual weight tracking (wet, dry, flower, trim)
- **Batch Destruction Operations**: Group multiple plants for destruction with mandatory reasons and compliance documentation
- **Plant Information Preservation**: All original plant data (tracking numbers, genetics, stages, rooms) preserved in batch operations
- **Batch Details Page**: Comprehensive view of batch operations with complete plant information and statistics
- **Individual Weight Tracking**: Separate weight recording for each plant in batch operations

### 🧭 **Complete Navigation Overhaul**
- **Comprehensive Navigation Bar**: Organized into 7 logical categories with 40+ accessible pages
- **Professional Index Page**: Complete system overview with live statistics and organized page access
- **Enhanced Dropdowns**: Plants, Genetics, Facilities, Operations, Business, Reports, and Compliance sections
- **Visual Organization**: Dropdown dividers and logical grouping for better user experience
- **Complete Page Coverage**: Every major system page now accessible through intuitive navigation

### 🎨 **Enhanced User Interface**
- **Professional Modal System**: Modern modal interface for batch operations with detailed inputs
- **Advanced Filtering**: Multi-criteria filtering for destroyed plants and batch operations
- **Visual Status Indicators**: Color-coded reason badges, operation type indicators, and status displays
- **Responsive Design**: Mobile-optimized layouts for all new pages and features
- **Real-time Statistics**: Live counters and data updates throughout the system

### 🔧 **Technical Infrastructure**
- **New Database Tables**: BatchHarvests, BatchDestructions, and BatchPlantDetails for comprehensive tracking
- **Enhanced APIs**: New endpoints for destroyed plants, batch operations, and batch details
- **Data Preservation**: Complete audit trail with all plant information maintained through operations
- **Compliance Ready**: Built-in compliance features with witness tracking and reason documentation

### ✨ **Key Features Added**
- **Mandatory Destruction Reasons**: Cannot destroy plants without selecting and documenting reason
- **Batch Weight Collection**: Individual plant weights collected during batch harvest operations
- **Complete Plant History**: All plant information preserved even after batch processing
- **Professional Reporting**: Detailed batch operation reports with plant breakdowns and statistics
- **Enhanced Navigation**: Professional navigation system with complete page organization

## [2.8.0] - 2025-01-19

### 🎨 **Administration Page Redesign**
- **Dashboard-Style Layout**: Complete redesign matching the main dashboard aesthetic
- **System Overview Cards**: Real-time statistics showing database size, records, companies, genetics, and rooms
- **Enhanced Visual Design**: Modern cards with gradients, progress bars, and professional styling
- **Better Organization**: Logical grouping of functions into Core Management, Business Management, Database Management, and System Tools

### ✨ **New Administration Features**
- **System Status Display**: Real-time system health indicator in page header
- **Enhanced Database Tools**: Health check validation, export options, and detailed information display
- **System Tools Section**: Cache clearing, update checking, system information, and PHP details
- **Professional Statistics**: Live counters for all major system components
- **Improved Navigation**: Better categorization and more intuitive access to functions

### 🔧 **Enhanced Functionality**
- **Real-time Data Loading**: Dynamic loading of system statistics and database information
- **Database Health Checks**: Comprehensive validation tools for database integrity
- **System Information Tools**: Detailed system and version information display
- **Better Error Handling**: Enhanced status messages and user feedback
- **Professional Gradients**: Color-coded sections for different types of operations

### 🎯 **User Experience Improvements**
- **Consistent Styling**: Matches dashboard design language throughout
- **Better Visual Hierarchy**: Clear section organization with modern card layouts
- **Enhanced Feedback**: Real-time status updates and system information
- **Professional Appearance**: Enterprise-grade administration interface
- **Mobile Responsive**: Optimized for all device sizes

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