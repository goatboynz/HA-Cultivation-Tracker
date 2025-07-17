# 📚 MediFlower Cultivation Tracker - Complete Documentation

## Table of Contents

1. [System Overview](#system-overview)
2. [Installation & Setup](#installation--setup)
3. [Core Features](#core-features)
4. [Plant Management](#plant-management)
5. [Mobile Features](#mobile-features)
6. [Reporting & Analytics](#reporting--analytics)
7. [Compliance & Legal](#compliance--legal)
8. [Technical Documentation](#technical-documentation)
9. [Troubleshooting](#troubleshooting)
10. [API Reference](#api-reference)

---

## System Overview

### What is MediFlower Cultivation Tracker?

MediFlower Cultivation Tracker is a comprehensive cannabis cultivation management system designed specifically for Home Assistant. It provides professional-grade plant tracking, compliance management, and cultivation analytics for legal cannabis operations of any size.

### Key Capabilities

- **Complete Plant Lifecycle Management** - Track every plant from seed/clone to harvest
- **Professional Compliance Tools** - Meet regulatory requirements with detailed tracking
- **Mobile-First Design** - Full functionality on smartphones and tablets
- **Photo Documentation** - Visual tracking with camera integration
- **Advanced Analytics** - Performance metrics and yield optimization
- **Multi-Room Support** - Manage complex cultivation facilities

### System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Home Assistant                           │
├─────────────────────────────────────────────────────────────┤
│  MediFlower Cultivation Tracker Add-on                     │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────┐ │
│  │   Web Interface │  │   Database      │  │   File      │ │
│  │   (PHP/JS/CSS)  │  │   (SQLite)      │  │   Storage   │ │
│  └─────────────────┘  └─────────────────┘  └─────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

---

## Installation & Setup

### Prerequisites

- **Home Assistant OS** or **Home Assistant Supervised**
- **Minimum 1GB RAM** available for the add-on
- **2GB storage space** (5GB recommended for photos)
- **Modern web browser** with JavaScript enabled

### Step-by-Step Installation

#### 1. Add Repository
```bash
# Add this repository to Home Assistant
https://github.com/goatboynz/HA-Cultivation-Tracker
```

#### 2. Install Add-on
1. Navigate to **Supervisor** → **Add-on Store**
2. Find **"MediFlower Cultivation Tracker"**
3. Click **Install**
4. Wait for installation to complete

#### 3. Configure Add-on
```yaml
# Basic configuration
name: "MediFlower Cultivation Tracker"
version: "2.0.0"
slug: "mediflower_cultivation_tracker"
ingress: true
ingress_port: 8420
```

#### 4. Start Add-on
1. Click **Start**
2. Enable **"Start on boot"** if desired
3. Enable **"Watchdog"** for automatic restart

#### 5. Initial Setup
1. Access via Home Assistant sidebar or direct URL
2. Navigate to **Administration** → **Initialize Database**
3. Click **"Initialize Database"** to create tables
4. Click **"Seed Default Rooms"** to create starter rooms
5. Add your first genetics strain
6. Start tracking plants!

### Configuration Options

#### Basic Settings
```yaml
# Database configuration
database_path: "/data/cultivation.db"
backup_enabled: true
backup_interval: "daily"

# Photo settings
photo_storage: "/data/photos"
max_photo_size: "10MB"
allowed_formats: ["jpg", "jpeg", "png", "webp"]

# Security settings
auth_required: true
session_timeout: "24h"
```

#### Advanced Settings
```yaml
# Performance tuning
max_memory: "512MB"
query_timeout: "30s"
cache_enabled: true

# Mobile settings
camera_enabled: true
offline_mode: true
touch_optimized: true

# Compliance settings
audit_logging: true
export_encryption: false
retention_period: "7 years"
```

---

## Core Features

### Plant Management System

#### Individual Plant Tracking
Every plant in the system has:
- **Unique Tracking Number** (CT-YYYY-XXXXXX format)
- **Complete Lifecycle History** from creation to disposal
- **Photo Documentation** at every stage
- **Detailed Notes** and observations
- **Source Tracking** (mother plant, seed stock, etc.)

#### Growth Stages
```
Seed/Clone → Vegetative → Flowering → Harvest/Destruction
     ↓           ↓           ↓            ↓
   Clone      Veg Room   Flower Room   Dry Room
   Room       (2-8 wks)  (8-12 wks)   (1-2 wks)
```

#### Plant Attributes
- **Basic Info**: Tracking number, plant tag, genetics
- **Location**: Current room, previous locations
- **Timeline**: Creation date, stage changes, key events
- **Physical**: Photos, measurements, observations
- **Source**: Mother plant, seed stock, or direct purchase
- **Status**: Growing, harvested, destroyed, or sent

### Room Management

#### Room Types
1. **Clone Room** - Propagation and early growth
2. **Vegetative Room** - Vegetative growth phase
3. **Flowering Room** - Flowering and bud development
4. **Mother Room** - Mother plant maintenance
5. **Dry Room** - Post-harvest drying
6. **Storage** - Equipment and supply storage

#### Room Features
- **Capacity Tracking** - Monitor plant counts vs. capacity
- **Environmental Logging** - Temperature, humidity, lighting
- **Plant Movement** - Track transfers between rooms
- **Utilization Analytics** - Efficiency and usage metrics

### Genetics Database

#### Strain Information
- **Basic Details**: Name, breeder, lineage
- **Cannabinoid Profile**: THC%, CBD%, other cannabinoids
- **Growing Characteristics**: Flowering time, yield expectations
- **Physical Traits**: Indica/Sativa ratio, growth pattern
- **Photos**: Visual strain identification
- **Performance Data**: Success rates, average yields

#### Genetics Management
- **Strain Library** - Comprehensive strain database
- **Performance Tracking** - Success rates and yields per strain
- **Photo Gallery** - Visual strain identification
- **Breeding Records** - Parent/offspring relationships

---

## Plant Management

### Adding New Plants

#### Source Selection
When adding plants, you can specify the source:

1. **From Mother Plant**
   - Select existing mother plant
   - Automatically inherits genetics
   - Creates parent-child relationship
   - Tracks clone success rates

2. **From Seed Stock**
   - Select from seed inventory
   - Updates seed stock usage
   - Tracks germination success
   - Links to seed batch information

3. **Clone/Cutting**
   - From external source
   - Manual genetics selection
   - Source documentation

4. **Purchased Plants**
   - From licensed suppliers
   - Compliance documentation
   - Source verification

#### Plant Creation Process
```
1. Select Source Type → 2. Choose Specific Source → 3. Set Plant Details
        ↓                        ↓                        ↓
   Mother/Seed/Clone    Specific Plant/Batch      Count, Room, Stage
        ↓                        ↓                        ↓
4. Generate Tracking → 5. Create Database → 6. Assign to Room
        ↓                        ↓                        ↓
   CT-2025-123456        Plant Record Created    Ready for Management
```

### Plant Lifecycle Management

#### Stage Progression
Plants naturally progress through stages:

1. **Clone Stage** (0-14 days)
   - Root development
   - Initial growth
   - Health monitoring
   - Success/failure tracking

2. **Vegetative Stage** (2-8 weeks)
   - Rapid growth phase
   - Training and pruning
   - Sex identification
   - Mother plant selection

3. **Flowering Stage** (8-12 weeks)
   - Bud development
   - Harvest timing
   - Quality assessment
   - Yield estimation

4. **Harvest/Destruction**
   - Final processing
   - Weight recording
   - Quality documentation
   - Compliance logging

#### Plant Actions
Available actions depend on plant stage and status:

- **Edit Plant** - Modify any plant attribute
- **Move Plant** - Transfer between rooms or stages
- **Take Photos** - Document plant condition
- **Add Notes** - Record observations
- **Harvest Plant** - Complete harvest process
- **Destroy Plant** - Compliance destruction logging

### Mother Plant Management

#### Mother Plant System
Mother plants are the foundation of clone production:

- **Dedicated Management** - Separate mother plant interface
- **Clone Production** - Track clones taken from each mother
- **Performance Metrics** - Success rates and clone quality
- **Maintenance Scheduling** - Pruning, feeding, health checks
- **Replacement Planning** - Mother plant lifecycle management

#### Taking Clones
Process for taking clones from mother plants:

1. **Select Mother Plant** - Choose healthy, productive mother
2. **Specify Clone Count** - Number of clones to take
3. **Choose Clone Room** - Destination for new clones
4. **Add Notes** - Document clone-taking process
5. **Generate Tracking** - Unique numbers for each clone
6. **Create Records** - Link clones to mother plant

### Harvest Management

#### Harvest Process
Comprehensive harvest tracking includes:

1. **Pre-Harvest Assessment**
   - Trichome examination
   - Harvest readiness indicators
   - Quality predictions
   - Timing optimization

2. **Harvest Execution**
   - Harvest date/time recording
   - Photo documentation
   - Initial weight measurement
   - Room transfer to drying

3. **Weight Tracking**
   - **Wet Weight** - Immediate post-harvest weight
   - **Dry Weight** - After drying process
   - **Flower Weight** - Usable flower material
   - **Trim Weight** - Trim and shake material

4. **Quality Assessment**
   - Overall quality rating
   - Trichome color analysis
   - Aroma profile documentation
   - Bud density evaluation

#### Harvest Data Points
```yaml
Harvest Record:
  plant_id: CT-2025-123456
  harvest_date: "2025-07-18T14:30:00"
  wet_weight: 245.7g
  dry_weight: 52.3g
  flower_weight: 38.9g
  trim_weight: 13.4g
  quality: "Premium"
  trichome_color: "Cloudy"
  aroma: "Citrus, Pine"
  density: "Dense"
  yield_percentage: 21.3%
```

### Destruction Management

#### Destruction Logging
Comprehensive destruction tracking for compliance:

1. **Destruction Reasons**
   - Disease/Infection
   - Pest Infestation
   - Poor Growth/Development
   - Hermaphrodite
   - Quality Control
   - Compliance Requirement
   - Other (specify)

2. **Documentation Requirements**
   - Destruction date/time
   - Detailed reason explanation
   - Photo evidence
   - Weight measurements
   - Witness information
   - Disposal method

3. **Weight Recording**
   - Plant material weight
   - Root system weight
   - Growing medium weight
   - Total disposal weight

4. **Compliance Features**
   - Audit trail creation
   - Regulatory reporting
   - Evidence preservation
   - Chain of custody

---

## Mobile Features

### Mobile-First Design

#### Responsive Interface
- **Adaptive Layout** - Optimizes for any screen size
- **Touch-Friendly** - Large buttons and touch targets
- **Gesture Support** - Swipe, pinch, and tap gestures
- **Fast Loading** - Optimized for mobile networks

#### Mobile Navigation
- **Hamburger Menu** - Collapsible navigation
- **Bottom Navigation** - Quick access to main sections
- **Breadcrumbs** - Clear navigation path
- **Back Button Support** - Native browser navigation

### Camera Integration

#### Photo Capture
- **Direct Camera Access** - Take photos directly in app
- **Front/Rear Camera** - Choose camera for best angle
- **Multiple Photos** - Capture multiple angles
- **Instant Preview** - Review photos before saving

#### Photo Management
- **Upload from Gallery** - Select existing photos
- **Photo Organization** - Categorize by type and date
- **Photo Editing** - Basic crop and rotate functions
- **Storage Optimization** - Automatic compression

#### Use Cases
- **Plant Documentation** - Regular growth photos
- **Issue Identification** - Document problems or diseases
- **Harvest Evidence** - Before/after harvest photos
- **Compliance Photos** - Destruction evidence

### Offline Capabilities

#### Core Functions Available Offline
- **View Plant Data** - Access cached plant information
- **Take Photos** - Capture photos for later upload
- **Add Notes** - Record observations offline
- **Basic Navigation** - Browse through plant records

#### Sync When Online
- **Photo Upload** - Automatic upload when connected
- **Data Sync** - Synchronize offline changes
- **Conflict Resolution** - Handle conflicting updates
- **Progress Indicators** - Show sync status

---

## Reporting & Analytics

### Report Categories

#### 1. Plant Reports
- **All Plants** - Complete plant inventory
- **Plants by Stage** - Breakdown by growth stage
- **Plants by Room** - Room-specific plant lists
- **Plant Timeline** - Lifecycle progression
- **Individual Plant History** - Complete plant record

#### 2. Genetics Reports
- **Genetics Performance** - Success rates by strain
- **Yield Analysis** - Average yields per genetics
- **Growth Timeline** - Days to harvest by strain
- **Quality Metrics** - Quality ratings by genetics
- **Genetics Inventory** - Current genetics in system

#### 3. Harvest Reports
- **Harvest Summary** - Total harvests and weights
- **Yield Trends** - Yield performance over time
- **Quality Analysis** - Quality distribution
- **Harvest Calendar** - Harvest scheduling
- **Weight Breakdown** - Flower vs. trim analysis

#### 4. Compliance Reports
- **Destruction Log** - All plant destructions
- **Movement Tracking** - Plant transfers and moves
- **Audit Trail** - Complete action history
- **Inventory Summary** - Current plant counts
- **Chain of Custody** - Plant source tracking

#### 5. Room Reports
- **Room Utilization** - Capacity and usage
- **Room Timeline** - Plant movement history
- **Efficiency Metrics** - Plants per square foot
- **Environmental Data** - Room conditions (if integrated)
- **Capacity Planning** - Future space requirements

#### 6. Mother Plant Reports
- **Mother Performance** - Clone success rates
- **Clone Production** - Clones taken per mother
- **Mother Health** - Health and maintenance records
- **Replacement Schedule** - Mother plant lifecycle
- **Genetics Preservation** - Strain maintenance

### Export Options

#### CSV Export
```csv
Plant_ID,Tracking_Number,Genetics,Stage,Room,Status,Created_Date,Days_Old
1,CT-2025-123456,OG Kush,Flower,Flower Room 1,Growing,2025-06-01,47
2,CT-2025-123457,Blue Dream,Veg,Veg Room 1,Growing,2025-06-15,33
```

#### Excel Export
- **Formatted Spreadsheets** - Professional formatting
- **Multiple Worksheets** - Separate sheets for different data
- **Charts and Graphs** - Visual data representation
- **Formulas** - Calculated fields and totals

#### Custom Reports
- **Date Range Filtering** - Specific time periods
- **Multi-Criteria Filtering** - Complex filter combinations
- **Custom Fields** - Select specific data columns
- **Scheduled Reports** - Automated report generation

### Analytics Dashboard

#### Key Performance Indicators (KPIs)
- **Total Active Plants** - Current growing plants
- **Average Days to Harvest** - Cultivation efficiency
- **Success Rate** - Percentage of successful harvests
- **Yield per Plant** - Average harvest weights
- **Room Utilization** - Capacity usage percentages

#### Trend Analysis
- **Growth Trends** - Plant count over time
- **Yield Trends** - Harvest performance trends
- **Quality Trends** - Quality improvements
- **Efficiency Trends** - Operational efficiency

#### Visual Analytics
- **Charts and Graphs** - Visual data representation
- **Heat Maps** - Room utilization visualization
- **Timeline Views** - Plant lifecycle visualization
- **Comparison Charts** - Genetics performance comparison

---

## Compliance & Legal

### Regulatory Compliance

#### Tracking Requirements
Most cannabis regulations require:
- **Unique Plant Identification** - Every plant must have unique ID
- **Seed-to-Sale Tracking** - Complete plant history
- **Weight Documentation** - All weights recorded and tracked
- **Movement Logging** - All plant transfers documented
- **Destruction Records** - Detailed destruction documentation

#### MediFlower Compliance Features
- ✅ **Unique Tracking Numbers** - CT-YYYY-XXXXXX format
- ✅ **Complete Chain of Custody** - From source to disposal
- ✅ **Detailed Weight Records** - All harvest and destruction weights
- ✅ **Movement Documentation** - Room transfers and stage changes
- ✅ **Photo Evidence** - Visual documentation of key events
- ✅ **Audit Trail** - Complete action history
- ✅ **Export Capabilities** - Compliance-ready reports

### Record Keeping

#### Required Records
1. **Plant Records**
   - Unique identifier
   - Source information
   - Growth stage history
   - Location history
   - Harvest or destruction data

2. **Inventory Records**
   - Current plant counts
   - Plant locations
   - Growth stages
   - Harvest weights

3. **Movement Records**
   - Transfer dates and times
   - Source and destination
   - Reason for transfer
   - Authorized personnel

4. **Destruction Records**
   - Destruction date and time
   - Reason for destruction
   - Method of destruction
   - Witness information
   - Photo evidence

#### Data Retention
- **Minimum 3 Years** - Most jurisdictions require 3-year retention
- **Up to 7 Years** - Some jurisdictions require longer retention
- **Automatic Archiving** - System can archive old records
- **Export for Storage** - Export data for long-term storage

### Audit Preparation

#### Audit Readiness
MediFlower helps prepare for regulatory audits:

1. **Complete Documentation** - All required records maintained
2. **Easy Access** - Quick retrieval of any plant record
3. **Export Capabilities** - Generate audit reports instantly
4. **Photo Evidence** - Visual proof of compliance
5. **Chain of Custody** - Complete plant history available

#### Audit Reports
Pre-built reports for common audit requirements:
- **Plant Inventory** - Current plant counts and locations
- **Destruction Log** - All plant destructions with reasons
- **Movement History** - Plant transfers and relocations
- **Harvest Records** - All harvests with weights and dates
- **Source Documentation** - Plant origins and genetics

### Legal Considerations

#### Disclaimer
This software is designed for use in jurisdictions where cannabis cultivation is legal. Users are responsible for:
- **Legal Compliance** - Ensuring all activities comply with local laws
- **Licensing Requirements** - Maintaining proper licenses
- **Regulatory Updates** - Staying current with changing regulations
- **Record Accuracy** - Maintaining accurate and truthful records

#### Privacy and Security
- **Local Data Storage** - All data stored locally on your system
- **No Cloud Dependencies** - No external services required
- **User Control** - Complete control over your data
- **Encryption Options** - Database encryption available

---

## Technical Documentation

### System Requirements

#### Minimum Requirements
- **RAM**: 1GB available
- **Storage**: 2GB free space
- **CPU**: ARM64, AMD64, or x86 architecture
- **Network**: Local network access
- **Browser**: Modern browser with JavaScript

#### Recommended Requirements
- **RAM**: 2GB available
- **Storage**: 5GB free space (for photos)
- **CPU**: Multi-core processor
- **Network**: Stable internet connection
- **Browser**: Chrome, Firefox, Safari, or Edge

### Database Schema

#### Core Tables
```sql
-- Plants table (main plant records)
Plants (
    id, tracking_number, genetics_id, status, growth_stage,
    room_id, plant_tag, notes, is_mother, mother_id,
    seed_stock_id, source_type, date_created, date_harvested,
    wet_weight, dry_weight, flower_weight, trim_weight,
    destruction_reason, destruction_notes, destruction_date
)

-- Genetics table (strain information)
Genetics (
    id, name, breeder, genetic_lineage, flowering_days,
    thc_percentage, cbd_percentage, indica_sativa_ratio,
    description, photo_url, created_date
)

-- Rooms table (cultivation spaces)
Rooms (
    id, name, room_type, description
)

-- SeedStock table (seed inventory)
SeedStock (
    id, batch_name, genetics_id, supplier, seed_count,
    used_count, purchase_date, expiry_date, storage_location,
    germination_rate, price, photo_path, notes
)

-- PlantPhotos table (photo documentation)
PlantPhotos (
    id, plant_id, file_path, file_name, file_size,
    upload_date, photo_type, description
)

-- HarvestRecords table (harvest tracking)
HarvestRecords (
    id, plant_id, harvest_date, wet_weight, dry_weight,
    flower_weight, trim_weight, quality, trichome_color,
    aroma, density, notes
)

-- DestructionRecords table (destruction logging)
DestructionRecords (
    id, plant_id, destruction_date, reason, method,
    plant_weight, root_weight, soil_weight, total_weight,
    notes, witness_name, compliance_notes
)
```

#### Relationships
```
Plants → Genetics (many-to-one)
Plants → Rooms (many-to-one)
Plants → Plants (mother-child relationship)
Plants → SeedStock (many-to-one)
Plants → PlantPhotos (one-to-many)
Plants → HarvestRecords (one-to-many)
Plants → DestructionRecords (one-to-many)
```

### File Structure
```
/addon_configs/
├── cultivation.db          # Main SQLite database
├── uploads/
│   ├── genetics/          # Genetics photos
│   ├── plants/            # Plant photos
│   │   ├── harvest/       # Harvest photos
│   │   └── destruction/   # Destruction evidence
│   └── seed_stock/        # Seed stock photos
├── backups/               # Database backups
└── logs/                  # System logs
```

### API Endpoints

#### Plant Management
```
GET  /get_plant_details.php?id={plant_id}
GET  /get_all_plants_detailed.php
GET  /get_individual_plants_by_stage.php?stage={stage}
POST /handle_edit_plant.php
POST /handle_receive_genetics.php
POST /handle_harvest_plant.php
POST /handle_destroy_plant.php
```

#### Photo Management
```
GET  /get_plant_photos.php?plant_id={plant_id}
POST /upload_plant_photo.php
POST /delete_plant_photo.php
```

#### Reporting
```
GET  /generate_report.php?type={report_type}
GET  /generate_report.php?type={type}&start_date={date}&end_date={date}
```

#### Room Management
```
GET  /get_all_rooms.php
GET  /get_rooms_by_type.php?type={room_type}
POST /handle_add_room.php
```

#### Genetics Management
```
GET  /get_genetics.php
GET  /get_genetics_detailed.php
GET  /get_genetics_details.php?id={genetics_id}
POST /handle_add_genetics.php
```

### Security Features

#### Input Validation
- **SQL Injection Prevention** - Parameterized queries
- **XSS Protection** - Output encoding and sanitization
- **File Upload Security** - Type validation and size limits
- **CSRF Protection** - Token-based request validation

#### Authentication
- **Session Management** - Secure session handling
- **Access Control** - Role-based permissions
- **Password Security** - Hashed password storage
- **Session Timeout** - Automatic logout

#### Data Protection
- **Database Encryption** - Optional database encryption
- **File Permissions** - Proper file system permissions
- **Backup Security** - Encrypted backup options
- **Audit Logging** - Security event logging

---

## Troubleshooting

### Common Issues

#### Installation Problems

**Issue**: Add-on won't install
```
Solution:
1. Check Home Assistant version compatibility
2. Ensure sufficient storage space (2GB minimum)
3. Check system logs for error messages
4. Try restarting Home Assistant
```

**Issue**: Database initialization fails
```
Solution:
1. Check file permissions on /data directory
2. Ensure SQLite is available
3. Check available disk space
4. Review add-on logs for specific errors
```

#### Performance Issues

**Issue**: Slow page loading
```
Solution:
1. Check available RAM (1GB minimum required)
2. Optimize database with VACUUM command
3. Clear browser cache
4. Reduce number of photos per plant
```

**Issue**: Photo upload failures
```
Solution:
1. Check file size (10MB maximum)
2. Verify file format (JPG, PNG, WebP supported)
3. Check available storage space
4. Ensure proper file permissions
```

#### Mobile Issues

**Issue**: Camera not working on mobile
```
Solution:
1. Grant camera permissions to browser
2. Use HTTPS connection (required for camera)
3. Try different browser (Chrome recommended)
4. Check device camera functionality
```

**Issue**: Touch interface not responsive
```
Solution:
1. Clear browser cache and cookies
2. Disable browser zoom
3. Update to latest browser version
4. Check for JavaScript errors in console
```

#### Data Issues

**Issue**: Plants not showing in reports
```
Solution:
1. Check plant status filters
2. Verify date range selections
3. Refresh browser cache
4. Check database integrity
```

**Issue**: Missing tracking numbers
```
Solution:
1. Run migration script: /migrate_tracking_numbers.php
2. Check database schema version
3. Manually assign tracking numbers if needed
4. Contact support if issue persists
```

### Diagnostic Tools

#### System Information
Access system information at: `/system_info.php`
- Database size and status
- Available storage space
- PHP configuration
- System performance metrics

#### Database Tools
- **Database Vacuum**: Optimize database performance
- **Integrity Check**: Verify database consistency
- **Backup Creation**: Manual backup generation
- **Migration Status**: Check schema version

#### Log Files
Check add-on logs for detailed error information:
```bash
# Home Assistant logs
ha addon logs mediflower_cultivation_tracker

# System logs location
/addon_configs/mediflower_cultivation_tracker/logs/
```

### Getting Help

#### Support Channels
1. **GitHub Issues** - Bug reports and feature requests
2. **GitHub Discussions** - Community support and questions
3. **Documentation Wiki** - Comprehensive guides and tutorials
4. **Home Assistant Community** - General Home Assistant support

#### Before Requesting Support
1. **Check Documentation** - Review relevant documentation sections
2. **Search Existing Issues** - Look for similar problems
3. **Gather Information** - System info, error messages, steps to reproduce
4. **Test in Safe Mode** - Disable other add-ons to isolate issues

#### Information to Include
- Home Assistant version
- Add-on version
- Browser and version
- Device type (desktop/mobile)
- Error messages
- Steps to reproduce
- Screenshots if applicable

---

## API Reference

### Authentication
All API endpoints require authentication. Include session cookies or authentication headers with requests.

### Response Format
All API responses use JSON format:
```json
{
  "success": true,
  "data": {...},
  "message": "Operation completed successfully"
}
```

Error responses:
```json
{
  "success": false,
  "error": "Error message",
  "code": "ERROR_CODE"
}
```

### Plant Endpoints

#### Get Plant Details
```http
GET /get_plant_details.php?id={plant_id}
```

Response:
```json
{
  "id": 123,
  "tracking_number": "CT-2025-123456",
  "genetics_name": "OG Kush",
  "growth_stage": "Flower",
  "room_name": "Flower Room 1",
  "status": "Growing",
  "date_created": "2025-06-01T10:00:00",
  "notes": "Plant notes here",
  "clone_count": 5
}
```

#### Get All Plants
```http
GET /get_all_plants_detailed.php
```

#### Get Plants by Stage
```http
GET /get_individual_plants_by_stage.php?stage={stage}
```

Parameters:
- `stage`: Clone, Veg, Flower, Mother

#### Update Plant
```http
POST /handle_edit_plant.php
```

Form Data:
```
plantId: 123
plantTag: "Custom Tag"
geneticsName: 1
growthStage: "Flower"
roomName: 2
status: "Growing"
notes: "Updated notes"
```

### Photo Endpoints

#### Get Plant Photos
```http
GET /get_plant_photos.php?plant_id={plant_id}
```

#### Upload Photo
```http
POST /upload_plant_photo.php
```

Form Data (multipart):
```
plant_id: 123
photo: [file]
photo_type: "general"
description: "Photo description"
```

#### Delete Photo
```http
POST /delete_plant_photo.php
```

Form Data:
```
photo_id: 456
```

### Reporting Endpoints

#### Generate Report
```http
GET /generate_report.php?type={report_type}
```

Report Types:
- `plants_all`
- `plants_by_stage`
- `plants_by_room`
- `mother_plants`
- `clone_tracking`
- `room_utilization`
- `genetics_performance`

#### Generate Date Range Report
```http
GET /generate_report.php?type={type}&start_date={date}&end_date={date}
```

Date Format: `YYYY-MM-DD`

### Room Endpoints

#### Get All Rooms
```http
GET /get_all_rooms.php
```

#### Get Rooms by Type
```http
GET /get_rooms_by_type.php?type={room_type}
```

Room Types:
- `Clone`
- `Veg`
- `Flower`
- `Mother`
- `Dry`
- `Storage`

### Genetics Endpoints

#### Get All Genetics
```http
GET /get_genetics.php
```

#### Get Genetics Details
```http
GET /get_genetics_details.php?id={genetics_id}
```

#### Get Genetics Performance
```http
GET /get_genetics_plant_stats.php?id={genetics_id}
```

### Seed Stock Endpoints

#### Get Seed Stocks
```http
GET /get_seed_stocks.php
```

#### Get Seed Stock Details
```http
GET /get_seed_stock_details.php?id={seed_stock_id}
```

---

This documentation provides comprehensive coverage of the MediFlower Cultivation Tracker system. For additional support or questions not covered here, please refer to the GitHub repository or community forums.