## 0.12.0 - Major Plant Stage Tracking Enhancement

### Added - Complete Plant Lifecycle Management
- **Plant Growth Stage Management**: Complete tracking system for Clone → Veg → Flower progression
- **Room Management System**: Create and manage different room types (Clone, Veg, Flower, Dry, Storage)
- **Plant Location Tracking**: Track which room each plant group is located in
- **Stage Movement Interface**: Easy-to-use interface for moving plants between stages and rooms
- **Dashboard Overview**: Real-time operational dashboard showing plant counts, room utilization, and harvest readiness
- **Enhanced Plant Pages**: Dedicated pages for each growth stage with stage-specific actions
- **Room Administration**: Full CRUD operations for room management in administration panel
- **Default Room Seeding**: One-click setup of standard room configuration
- **Enhanced Current Plants View**: Organized display by stage and room with summary statistics

### Database Changes
- Added `Rooms` table with room types and descriptions
- Enhanced `Plants` table with `growth_stage`, `room_id`, and `date_stage_changed` fields
- Updated database initialization to support new schema

### New Pages
- `dashboard.php` - Operational overview dashboard
- `plants_clone.php` - Clone stage plant management
- `plants_veg.php` - Vegetative stage plant management  
- `plants_flower.php` - Flowering stage plant management
- `manage_rooms.php` - Room administration interface

### New API Endpoints
- `get_plants_by_stage.php` - Retrieve plants filtered by growth stage
- `get_rooms_by_type.php` - Retrieve rooms filtered by type
- `move_plants.php` - Move plants between stages and rooms
- Room management APIs: `add_room.php`, `delete_room.php`, `get_all_rooms.php`
- Plant action APIs: `harvest_plants_action.php`, `destroy_plants.php`, `move_plants_room.php`

### Enhanced Features
- Updated navigation with direct access to plant stages and dashboard
- Enhanced receive genetics workflow with room assignment
- Improved plant tracking with stage and room context
- Better operational visibility with dashboard metrics
- Setup guide documentation for new features

## 0.11.1

 - Added "Hide rows with zero values" to some of the reports so we don't see empty rows

## 0.11

- Added Chain of Custody (CoC) document upload page
- Fixes for Shipping Manifest generation

## 0.10

- Added file upload for SOPs, Offtakes, Licenses, CoCs etc
- Fix shipping manifest generation
- Fix Pacific/Auckland timezone

## 0.9.8.1

- Fix theme toggle so it works in the materials-out (missing js include) for easier copy/paste into emails

## 0.9.8

- Fix annual reporting for previous year
- Fix last months materials out data
- Update Chain of Custody PDF generation, now printable and formatted nicely

## 0.9.7.2

- Correctly separated the "Out" so it only counts "Sent" now, with "Harvested" being separate
- Fix date query
- Fix binding genetics correctly

## 0.9.6

- Added Company Name / License # to the reporting pages to make it easier to copy/paste

## 0.9.5

- Fixed this/last months reporting, include company address as required by MCA
- Fixed title of Last Months to indicate correctly it reports on last months product out

## 0.9.4

- Added Docs instructions
- Minor page renaming in Reporting

## 0.9.3

- Fixed outgoing to now be plants + flower, in accordance with regulatory reporting requirements
- WIP: CoC generation improvements, still incomplete
- CoC save as PDF now too
- Tidied some page titles
- Removed the logout function (legacy), no longer needed

## 0.9.2

- Helpful info explaining what each page is for
- Clarified what's incomplete / coming soon

## 0.9.1

- Minor tweaks to the cosmetics
- Adjusted own company saving, so it takes you back to the admin page on a success (Helps with flow)
- Added icon / logo

## 0.9

- Initial pre-release (bugs expected)
