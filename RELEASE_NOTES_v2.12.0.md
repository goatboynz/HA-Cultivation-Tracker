# 🌿 Cultivation Tracker v2.12.0

## 🎉 **Major Release: Multi-Select Batch Operations & Enhanced Plant Management**

### 📋 **Quick Update Instructions for Home Assistant**
1. Go to **HACS** → **Integrations** → **Cultivation Tracker**
2. Click **Update** or **Redownload** to get version 2.12.0
3. Restart Home Assistant
4. The addon will automatically update to the new version

---

## ✨ **What's New in v2.12.0**

### 🔄 **Revolutionary Multi-Select Batch Operations**
- **Checkbox Selection**: Select multiple plants with checkboxes on Clone and Veg pages
- **Batch Move Operations**:
  - **Clone → Veg**: Move multiple clones to vegetative stage simultaneously
  - **Veg → Flower**: Move multiple veg plants to flowering stage
  - **Veg → Mother**: Convert multiple veg plants to mother plants
- **Batch Destruction**: Destroy multiple selected plants with single confirmation
- **Smart Interface**: Batch operations panel appears only when plants are selected
- **Selection Tools**: Select All, Clear Selection, and real-time selection counter

### 🗑️ **Professional Plant Deletion System**
- **Delete Plant Button**: Prominent red delete button on Edit Plant page
- **Double Safety Confirmation**: Two confirmation dialogs prevent accidental deletion
- **Complete Data Cleanup**: Automatically removes:
  - Plant photos and files
  - Batch operation records
  - Updates child plants (removes mother references)
- **Audit Trail**: Creates PlantDeletionLog for compliance tracking
- **Transaction Safety**: Database transactions ensure complete deletion or rollback

### 📸 **Enhanced Photo Management System**
- **Fixed Photo Uploads**: Resolved all photo saving issues in edit plant form
- **Multiple Upload Support**: Select and upload multiple photos at once
- **Camera Integration**: Take photos directly in browser with live preview
- **Photo Preview System**: View and remove photos before saving
- **Current Photo Display**: Shows existing plant photos with timestamps
- **Mobile Camera Support**: Works perfectly on phones and tablets
- **Proper File Storage**: Photos saved to uploads/plants/ with database records

### 🎨 **Professional User Interface**
- **Dynamic Batch Panel**: Appears when plants are selected, hides when none selected
- **Real-Time Feedback**: Live counter showing "X plants selected"
- **Visual Selection**: Checkboxes with select all/indeterminate states
- **Mobile Optimized**: All features work perfectly on mobile devices
- **Consistent Styling**: Modern design across all batch operations

---

## 🚀 **How to Use New Features**

### **Multi-Select Batch Operations**
1. Go to **Clone Stage** or **Veg Stage** pages
2. Use checkboxes to select multiple plants
3. Batch operations panel appears automatically
4. Choose your operation:
   - **Move to Next Stage**: Batch move plants
   - **Make Mothers**: Convert veg plants to mothers
   - **Destroy Selected**: Batch destroy with confirmation

### **Plant Deletion**
1. Go to **Edit Plant** page for any plant
2. Scroll to bottom and click **🗑️ Delete Plant** button
3. Confirm twice for safety
4. Plant and all related data will be permanently removed

### **Enhanced Photo Management**
1. In **Edit Plant** page, scroll to **📸 Plant Photos** section
2. **Upload Photos**: Click "📁 Upload Photos" to select files
3. **Take Photos**: Click "📷 Take Photo" to use camera
4. **Preview**: See photos before saving
5. **Remove**: Click × on any preview to remove

---

## 🔧 **Technical Improvements**

### **Backend Enhancements**
- New `delete_plant.php` endpoint for safe plant deletion
- Enhanced batch operation handling in existing endpoints
- Improved photo upload processing with proper validation
- Transaction-based operations for data integrity

### **Frontend Improvements**
- Advanced JavaScript for multi-select functionality
- Real-time UI updates and feedback
- Enhanced form handling for photo uploads
- Mobile-responsive batch operation interfaces

### **Database Enhancements**
- New PlantDeletionLog table for audit trail
- Improved referential integrity handling
- Better photo storage and management
- Optimized batch operation queries

---

## 📊 **Version History**
- **v2.12.0** - Multi-select batch operations, plant deletion, enhanced photos
- **v2.11.0** - Advanced tracking numbers and multi-mother clone selection
- **v2.10.0** - Enhanced Edit Plant page and fixed mother plant selection
- **v2.9.0** - Advanced destruction tracking and batch operations
- **v2.8.0** - Administration page redesign

---

## 🔗 **GitHub Repository**
**Repository**: https://github.com/goatboynz/HA-Cultivation-Tracker
**Latest Release**: v2.12.0
**Tag**: v2.12.0

---

## 🛠️ **Installation/Update Process**

### **For Home Assistant Users:**
1. **HACS Method** (Recommended):
   - Open HACS → Integrations
   - Find "Cultivation Tracker"
   - Click "Update" or "Redownload"
   - Restart Home Assistant

2. **Manual Method**:
   - Download the latest release from GitHub
   - Extract to your addons folder
   - Restart Home Assistant

---

## 🎯 **What to Test**

### **Multi-Select Operations**
1. Go to **Clone Stage** or **Veg Stage**
2. Select multiple plants using checkboxes
3. Try batch move operations
4. Test select all/clear functionality
5. Verify batch destruction works with confirmation

### **Plant Deletion**
1. Edit any plant
2. Click the delete button
3. Verify double confirmation works
4. Check that plant and related data are removed
5. Verify audit log is created

### **Photo Management**
1. Edit a plant and go to Photos section
2. Upload multiple photos
3. Try camera functionality
4. Verify photos save correctly
5. Test photo preview and removal

---

## 🐛 **Known Issues**
- None currently identified
- If you encounter issues, check browser console for errors
- Camera functionality requires HTTPS in production

---

## 📞 **Support**
- **GitHub Issues**: Report bugs or feature requests
- **Documentation**: Check the repository README
- **Debugging**: Use browser developer tools for troubleshooting

---

## 🎉 **Ready to Use!**
Version 2.12.0 brings professional-grade batch operations and plant management to your cultivation system. The multi-select functionality dramatically improves efficiency for managing large numbers of plants, while the enhanced photo system and deletion capabilities provide complete plant lifecycle management.

**Happy Growing!** 🌿✨