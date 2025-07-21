# 🌿 Cultivation Tracker v2.11.0

## 🎉 **Major Release: Advanced Tracking Numbers & Multi-Mother Clone Selection**

### 📋 **Quick Update Instructions for Home Assistant**
1. Go to **HACS** → **Integrations** → **Cultivation Tracker**
2. Click **Update** or **Redownload** to get version 2.11.0
3. Restart Home Assistant
4. The addon will automatically update to the new version

---

## ✨ **What's New in v2.11.0**

### 🔢 **Revolutionary Tracking Number System**
- **Mother-Based Tracking**: Clones now get tracking numbers based on their mother plant ID
  - Example: If mother plant ID is 5, clones will be: `5-01`, `5-02`, `5-03`, etc.
- **Sequential Numbering**: Automatic sequential numbering for each mother plant
- **Smart Fallback**: Non-clone plants still use standard `CT-YYYY-XXXXXX` format
- **Unique Identification**: Easy to trace any plant back to its mother

### 👥 **Multi-Mother Clone Selection**
- **Select Multiple Mothers**: Choose multiple mother plants when adding clones
- **Distribution Management**: Specify exactly how many clones to take from each mother
- **Real-Time Validation**: Live feedback ensures clone distribution equals total plant count
- **Visual Status Indicators**: Color-coded feedback (✅ correct, ⚠️ remaining, ❌ over-distributed)
- **Dynamic Interface**: Add/remove mother plant selections as needed

### 📅 **Enhanced Date Management**
- **Custom Date Selection**: Choose the exact date when plants were added to the system
- **Default Current Time**: Automatically defaults to current date and time
- **Proper Timeline**: Better tracking of when plants actually entered your system
- **Historical Accuracy**: Important for compliance and growth tracking

### 🎨 **Professional User Interface**
- **Modern Multi-Select**: Clean, professional interface for selecting multiple mothers
- **Distribution Dashboard**: Real-time visual feedback on clone distribution
- **Enhanced Validation**: Better form validation with helpful error messages
- **Mobile Optimized**: Perfect experience on phones and tablets

---

## 🚀 **How It Works**

### **Adding Clones from Multiple Mothers**
1. Go to **Add Plants** (Receive Genetics)
2. Select **"From Mother Plant(s)"** as source type
3. Choose your date
4. Click **"Add Another Mother Plant"** to select multiple mothers
5. Specify how many clones from each mother
6. System validates that total equals your plant count
7. Submit to create plants with proper tracking numbers

### **Tracking Number Examples**
- **Mother Plant ID 3**: Clones will be `3-01`, `3-02`, `3-03`, etc.
- **Mother Plant ID 15**: Clones will be `15-01`, `15-02`, `15-03`, etc.
- **Seed Plants**: Still use `CT-2025-123456` format
- **Purchased Plants**: Still use `CT-2025-123456` format

---

## 🔧 **Technical Improvements**

### **Enhanced Backend**
- New `generateMultiMotherTrackingNumbers()` function
- Improved `handle_receive_genetics.php` for multi-mother support
- Enhanced `get_mother_plants.php` with better error handling
- Robust form validation and error handling

### **Frontend Enhancements**
- Extended CSS for multi-mother interface styling
- Real-time JavaScript validation
- Dynamic form elements
- Professional visual feedback system

---

## 📊 **Version History**
- **v2.11.0** - Advanced tracking numbers and multi-mother clone selection
- **v2.10.0** - Enhanced Edit Plant page and fixed mother plant selection
- **v2.9.0** - Advanced destruction tracking and batch operations
- **v2.8.0** - Administration page redesign
- **v2.7.0** - Database management and enhanced filtering

---

## 🔗 **GitHub Repository**
**Repository**: https://github.com/goatboynz/HA-Cultivation-Tracker
**Latest Release**: v2.11.0
**Tag**: v2.11.0

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

### **Multi-Mother Clone Selection**
1. Go to **Add Plants** → Select "From Mother Plant(s)"
2. Add multiple mother plants using the "Add Another Mother Plant" button
3. Distribute clones between mothers (e.g., 5 from Mother A, 3 from Mother B)
4. Verify the system validates your distribution
5. Submit and check the generated tracking numbers

### **Tracking Number System**
1. Create clones from different mother plants
2. Verify tracking numbers follow the `MOTHER_ID-XX` format
3. Check that sequential numbering works correctly
4. Verify non-clone plants still use standard format

### **Date Selection**
1. Test selecting custom dates when adding plants
2. Verify dates are properly stored and displayed
3. Check that default date is current time

---

## 🐛 **Known Issues**
- None currently identified
- If you encounter issues, use the debugging utilities:
  - `/test_mother_plants.php` - Test mother plant queries
  - `/create_test_mothers.php` - Create test mother plants if needed

---

## 📞 **Support**
- **GitHub Issues**: Report bugs or feature requests
- **Documentation**: Check the repository README
- **Debugging**: Use the built-in test utilities

---

## 🎉 **Ready to Use!**
Version 2.11.0 brings professional-grade tracking and clone management to your cultivation operation. The new mother-based tracking system makes it easy to trace any plant back to its source, while the multi-mother selection streamlines your cloning workflow.

**Happy Growing!** 🌿✨