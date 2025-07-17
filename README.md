# 🌱 MediFlower Cultivation Tracker

**Professional Cannabis Cultivation Management System for Home Assistant**

[![Version](https://img.shields.io/badge/version-2.0.0-green.svg)](https://github.com/goatboynz/HA-Cultivation-Tracker)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Home Assistant](https://img.shields.io/badge/Home%20Assistant-Compatible-blue.svg)](https://www.home-assistant.io/)

## 📋 Overview

MediFlower Cultivation Tracker is a comprehensive cannabis cultivation management system designed as a Home Assistant add-on. It provides professional-grade plant tracking, compliance management, and cultivation analytics for legal cannabis operations.

### 🎯 Key Features

- **🌿 Complete Plant Lifecycle Management** - Track plants from seed/clone to harvest
- **📱 Mobile-Responsive Design** - Full functionality on phones, tablets, and desktops
- **📷 Photo Documentation** - Upload or capture photos directly in the app
- **🏷️ Unique Plant Tracking** - Every plant gets a unique tracking number (CT-YYYY-XXXXXX)
- **👩‍🌾 Mother Plant Management** - Dedicated mother plant tracking and clone management
- **🌱 Seed Stock Management** - Track seed inventory with expiration and germination rates
- **📊 Comprehensive Reporting** - 12+ report types with CSV and Excel export
- **⚖️ Harvest Tracking** - Record wet/dry weights, flower/trim weights
- **🗑️ Destruction Logging** - Detailed destruction records with reasons and compliance
- **🏠 Room Management** - Multi-room support with capacity tracking
- **📈 Analytics Dashboard** - Real-time cultivation metrics and performance data

## 🚀 Quick Start

### Prerequisites
- Home Assistant OS or Supervised installation
- Minimum 1GB RAM available
- 2GB storage space

### Installation

1. **Add Repository to Home Assistant:**
   ```
   https://github.com/goatboynz/HA-Cultivation-Tracker
   ```

2. **Install the Add-on:**
   - Go to Supervisor → Add-on Store
   - Find "MediFlower Cultivation Tracker"
   - Click Install

3. **Configure and Start:**
   - Set your configuration options
   - Start the add-on
   - Access via Home Assistant sidebar or direct URL

4. **Initial Setup:**
   - Navigate to Administration → Initialize Database
   - Create default rooms
   - Add your first genetics
   - Start tracking plants!

## 🏗️ System Architecture

### Database Schema
- **Plants** - Individual plant records with full lifecycle data
- **Genetics** - Strain database with THC/CBD, flowering times, photos
- **Rooms** - Multi-room support (Clone, Veg, Flower, Mother, Dry, Storage)
- **SeedStock** - Seed inventory management
- **HarvestRecords** - Detailed harvest data and weights
- **DestructionRecords** - Compliance destruction logging
- **PlantPhotos** - Photo documentation system

### Core Components
- **Plant Management** - CRUD operations for individual plants
- **Source Tracking** - Track plants from mother plants or seed stock
- **Stage Management** - Clone → Veg → Flower → Harvest workflow
- **Weight Tracking** - Wet, dry, flower, and trim weight recording
- **Photo System** - Upload or camera capture with mobile support
- **Reporting Engine** - Dynamic reports with export capabilities

## 📱 Mobile Features

### Camera Integration
- **Direct Photo Capture** - Take photos directly in the app
- **Upload Support** - Upload existing photos from device
- **Evidence Documentation** - Photo evidence for harvests and destructions

### Responsive Design
- **Touch-Friendly Interface** - Optimized for mobile interaction
- **Adaptive Layout** - Works seamlessly on all screen sizes
- **Offline Capability** - Core functions work without internet

## 📊 Reporting & Analytics

### Available Reports
1. **Plant Reports**
   - All Plants with full details
   - Plants by Growth Stage
   - Plants by Room
   - Mother Plants tracking
   - Clone relationship mapping

2. **Harvest Reports**
   - Harvest summaries with weights
   - Yield analysis and trends
   - Quality assessments
   - Timeline tracking

3. **Compliance Reports**
   - Destruction records
   - Plant movement tracking
   - Inventory summaries
   - Audit trails

4. **Analytics**
   - Genetics performance
   - Room utilization
   - Success rates
   - Growth timelines

### Export Options
- **CSV Export** - Standard comma-separated values
- **Excel Export** - Microsoft Excel compatible format
- **Print-Friendly** - Optimized for printing

## 🔧 Configuration

### Basic Configuration
```yaml
name: "MediFlower Cultivation Tracker"
version: "2.0.0"
slug: "mediflower_cultivation_tracker"
description: "Professional cannabis cultivation management system"
```

### Advanced Options
- **Database Path** - Custom database location
- **Photo Storage** - Photo storage configuration
- **Backup Settings** - Automated backup options
- **User Management** - Multi-user support settings

## 🌿 Plant Management Workflow

### 1. Adding Plants
```
Source Selection → Plant Details → Room Assignment → Tracking Number Generation
```

### 2. Growth Stages
```
Clone → Vegetative → Flowering → Harvest/Destruction
```

### 3. Mother Plant System
```
Add Mother → Take Clones → Track Relationships → Performance Analytics
```

### 4. Harvest Process
```
Quality Assessment → Weight Recording → Photo Documentation → Room Transfer
```

## 📋 Compliance Features

### Tracking Requirements
- **Unique Identifiers** - Every plant has a unique tracking number
- **Chain of Custody** - Complete plant history from source to end
- **Weight Documentation** - All weights recorded and tracked
- **Photo Evidence** - Visual documentation of key events
- **Destruction Records** - Detailed destruction logging with reasons

### Audit Trail
- **Complete History** - Every plant action is logged
- **User Attribution** - Track who performed each action
- **Timestamp Accuracy** - Precise date/time recording
- **Export Capability** - Generate compliance reports

## 🛠️ Technical Specifications

### System Requirements
- **RAM**: 1GB minimum, 2GB recommended
- **Storage**: 2GB minimum, 5GB recommended for photos
- **CPU**: ARM64, AMD64, or x86 architecture
- **Network**: Local network access required

### Supported Platforms
- **Home Assistant OS** - Full support
- **Home Assistant Supervised** - Full support
- **Home Assistant Container** - Limited support
- **Home Assistant Core** - Manual installation required

### Browser Compatibility
- **Chrome/Chromium** - Full support including camera
- **Firefox** - Full support including camera
- **Safari** - Full support including camera
- **Edge** - Full support including camera
- **Mobile Browsers** - Optimized mobile experience

## 🔒 Security & Privacy

### Data Protection
- **Local Storage** - All data stored locally on your system
- **No Cloud Dependencies** - No external services required
- **Encrypted Storage** - Database encryption available
- **Access Control** - User authentication and authorization

### Privacy Features
- **No Telemetry** - No usage data collected
- **No External Calls** - No data sent to external services
- **Local Processing** - All operations performed locally
- **User Control** - Complete control over your data

## 📚 Documentation

### User Guides
- [Quick Start Guide](docs/quick-start.md)
- [Plant Management Guide](docs/plant-management.md)
- [Reporting Guide](docs/reporting.md)
- [Mobile Usage Guide](docs/mobile-guide.md)

### Technical Documentation
- [Installation Guide](docs/installation.md)
- [Configuration Reference](docs/configuration.md)
- [API Documentation](docs/api.md)
- [Troubleshooting Guide](docs/troubleshooting.md)

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Getting Help
- **Issues**: [GitHub Issues](https://github.com/goatboynz/HA-Cultivation-Tracker/issues)
- **Discussions**: [GitHub Discussions](https://github.com/goatboynz/HA-Cultivation-Tracker/discussions)
- **Documentation**: [Wiki](https://github.com/goatboynz/HA-Cultivation-Tracker/wiki)

### Community
- **Home Assistant Community**: [Forum Thread](https://community.home-assistant.io/)
- **Discord**: [Join our Discord](https://discord.gg/cultivation-tracker)

## 🙏 Acknowledgments

- Home Assistant team for the excellent platform
- Cannabis cultivation community for feedback and testing
- Open source contributors who made this possible

## ⚖️ Legal Notice

This software is designed for use in jurisdictions where cannabis cultivation is legal. Users are responsible for ensuring compliance with all applicable local, state, and federal laws. The developers assume no responsibility for illegal use of this software.

---

**MediFlower Cultivation Tracker** - Professional cannabis cultivation management for the modern grower.

*Made with 🌱 for the cannabis community*