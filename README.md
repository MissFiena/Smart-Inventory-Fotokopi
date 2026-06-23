
# 🗂️ Smart Inventory: Inventory & Stock Tracking System for Fotokopi

> A web-based inventory management system developed for Fotokopi to manage stock efficiently and systematically.

![Version](https://img.shields.io/badge/Version-1.0-blue)
![Status](https://img.shields.io/badge/Status-In%20Progress-yellow)
![Course](https://img.shields.io/badge/Course-DES3073-green)

---

## 👥 Group Members & Responsibilities

| No. | Name | Module | Role |
|-----|------|--------|------|
| 1 | Shafiena binti Usri | Inventory Quality Score & Smart Usage Prediction | Leader |
| 2 | Miza Nafisah binti Imran | Item/Product Management & Stock Check-In/Check-Out | Member |
| 3 | Farysha Adella binti Abdullah | User Authentication & Roles | Member |
| 4 | Farah Nabila binti Shamsul Anuar | Dashboard & Reports/Notifications | Member |
| 5 | Zarith Sufizah binti Abu Bakar | Stock Expiry Reminder & Waste/Loss Tracking | Member |

---

## 📌 Project Description

The **Fotokopi Smart Inventory System** is a web-based platform designed to help Fotokopi manage inventory operations efficiently. The system supports:
- Stock monitoring & tracking
- Stock check-in and check-out
- Expiry tracking & waste/loss recording
- Dashboard reporting & notifications
- Smart inventory analysis & health scoring

---

## ✅ Functional Requirements

| FR_ID | Requirement | Assigned To | Status |
|-------|------------|-------------|--------|
| FR001 | User Login/Logout | Farysha Adella | 🔄 In Progress |
| FR002 | Recover User Password | Farysha Adella | 🔄 In Progress |
| FR003 | View User Role | Farysha Adella | 🔄 In Progress |
| FR004 | View Audit Logs | Farysha Adella | 🔄 In Progress |
| FR005 | Create Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR006 | Update Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR007 | Delete Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR008 | View Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR009 | Categorize Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR010 | Search Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR011 | Filter Inventory Item | Miza Nafisah | 🔄 In Progress |
| FR012 | Manage Supplier Details | Miza Nafisah | 🔄 In Progress |
| FR013 | Record Stock Check-In | Miza Nafisah | 🔄 In Progress |
| FR014 | Record Stock Check-Out | Miza Nafisah | 🔄 In Progress |
| FR015 | Auto Update Stock Level | Miza Nafisah | 🔄 In Progress |
| FR016 | View Stock Dashboard | Farah Nabila | 🔄 In Progress |
| FR017 | View Low Stock Alert | Farah Nabila | 🔄 In Progress |
| FR018 | View Overstock Alert | Farah Nabila | 🔄 In Progress |
| FR019 | Generate Stock Report | Farah Nabila | 🔄 In Progress |
| FR020 | View Stock Transaction History | Farah Nabila | 🔄 In Progress |
| FR021 | View Expiry Reminders | Zarith Sufizah | 🔄 In Progress |
| FR022 | Receive Expiry Notification | Zarith Sufizah | 🔄 In Progress |
| FR023 | Record Waste & Loss | Zarith Sufizah | 🔄 In Progress |
| FR024 | View Waste & Loss Summary | Zarith Sufizah | 🔄 In Progress |
| FR025 | View Inventory Health Score | Shafiena Usri | 🔄 In Progress |
| FR026 | View Smart Usage Predictions | Shafiena Usri | 🔄 In Progress |
| FR027 | View Restock Suggestions | Shafiena Usri | 🔄 In Progress |

---

## ⚙️ Non-Functional Requirements

| QR_ID | Type | Requirement | Assigned To | Status |
|-------|------|------------|-------------|--------|
| QR001 | Security | Only authenticated users can access the system. Unauthorized access must be blocked. | Farysha Adella | 🔄 In Progress |
| QR002 | Security | Role-based access strictly enforced. Staff cannot access Admin-only functions. | Farysha Adella | 🔄 In Progress |
| QR003 | User Friendly | Login & password recovery interface must be simple and straightforward. | Farysha Adella | 🔄 In Progress |
| QR004 | Performance | Login/logout response within 3 seconds under normal network conditions. | Farysha Adella | 🔄 In Progress |
| QR005 | Accuracy | Stock levels updated instantly and accurately after every check-in or check-out. | Miza Nafisah | 🔄 In Progress |
| QR006 | User Friendly | Dashboard displays real-time stock overview, low stock and overstock alerts clearly. | Farah Nabila | 🔄 In Progress |
| QR007 | Performance | Dashboard loads and reflects latest records within 3 seconds. | Farah Nabila | 🔄 In Progress |
| QR008 | Timeliness | Low stock alerts and expiry reminders triggered within 24 hours of condition being met. | Zarith Sufizah | 🔄 In Progress |
| QR009 | Accuracy | Inventory Health Score recalculated automatically each time stock data is updated. | Shafiena Usri | 🔄 In Progress |
| QR010 | Accuracy | Smart usage predictions based on at least 7 days of recorded stock transaction history. | Shafiena Usri | 🔄 In Progress |
| QR011 | Maintainability | Complete uneditable audit log of all user activities with accurate timestamps. | Farysha Adella | 🔄 In Progress |
| QR012 | Integrity | System prevents negative stock values, duplicate items and incomplete waste/loss records. | Miza Nafisah | 🔄 In Progress |
| QR013 | User Friendly | Search and filter returns relevant results instantly within a few clicks. | Miza Nafisah | 🔄 In Progress |
| QR014 | Performance | Stock reports generated and displayed within 5 seconds of Admin's request. | Farah Nabila | 🔄 In Progress |
| QR015 | Information | Waste and loss summary accurately reflects all recorded entries. Accessible to Admin only. | Zarith Sufizah | 🔄 In Progress |

---

## 🔗 Links
- 📋 Jira Board: [https://shafiena123.atlassian.net/jira/software/projects/SCRUM/boards/1]
- 📄 SRS Document: [Version 1.0 - 13/05/2026]

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML, CSS, JavaScript, Bootstrap |
| Backend | PHP (Laravel Framework) |
| Database | MySQL |
| Server | Apache (XAMPP) |
| Version Control | Git & GitHub |

---

*Last updated: May 2026 | DES3073 | Universiti Pendidikan Sultan Idris*
>>>>>>> 0d3291bf26bb8f445a7c0d04f90cbc16bb9c022b
