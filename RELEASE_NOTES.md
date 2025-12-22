# Release v1.0.0 - Initial Official Launch

We are excited to announce the first official release of the **Certificate Authority & API Management System**. This version provides a complete foundation for managing internal PKI and secure API access.

## ✨ Key Features
- **CA management**: Full lifecycle support for Root and Intermediate CAs.
- **Certificate Issuance**: Dynamic generation of leaf certificates with customizable SANs.
- **API Key Infrastructure**: Secure key generation, status toggling, and real-time usage tracking.
- **Advanced Dashboard**: Real-time metrics, issuance trends, and server latency monitoring.
- **Developer Experience**: Interactive API documentation with code snippets (cURL, JS, PHP, Python).
- **Hosting Friendly**: Included standalone Web Key Generator and manual database import scripts.

## 🛠️ Included in this Release
- **Source Code**: Full Laravel 12 / Tailwind v4 source.
- **Database Schema**: Pre-configured `install.sql` in the `database/` folder.
- **Key-Gen Tool**: Standalone utility in `public/key-gen.html`.

## 🚀 Installation Overview
For users with terminal access:
```bash
composer install && npm install && npm run build
php artisan migrate --seed
```

For Shared Hosting users:
1. Download the `app-v1.0.0-ready.zip` attachment.
2. Upload and extract to your server.
3. Import `database/install.sql` via phpMyAdmin.
4. Configure `.env` using our provided `key-gen.html`.

---
*Thank you for using DyDev TrustLab solutions!*
