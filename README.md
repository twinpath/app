# Certificate Authority & API Management System

A robust, modern platform for managing Root CAs, Intermediate CAs, and Leaf Certificates with an integrated API management system. Built on **Laravel 12**, **Tailwind CSS v4**, and **Alpine.js**.

## 🚀 Key Features

*   **CA Management**: Securely manage Root and Intermediate CAs.
*   **Certificate Issuance**: Issue and manage Leaf certificates for users.
*   **API Key System**: Advanced API key management with:
    *   **Regeneration**: Securely rotate keys with a single click.
    *   **Activity Tracking**: Real-time "Last Used" monitoring.
    *   **Public/Private Endpoints**: Documentation with interactive tabs and code snippets.
*   **AJAX-Powered UI**: Zero-refresh search, pagination, and status toggles.
*   **Dynamic Dashboard**: Real-time metrics, certificate issuance trends, and server latency monitoring.
*   **Modern Interactive UI**: High-performance dashboard with vibrant metrics and dark mode support.

## 🛠️ Built With

*   **Laravel 12**: Secure and scalable backend framework.
*   **Tailwind CSS v4**: Modern, utility-first styling.
*   **Alpine.js**: Lightweight reactivity.
*   **Chart.js**: Visual trend analysis.

## 🚦 Quick Start

### 1. Requirements
*   **PHP 8.2+** with the following extensions:
    *   `openssl` (Required for SSL/TLS operations)
    *   `zip` (Required for certificate bundle downloads)
    *   `bcmath` (Required for large serial number handling)
    *   `mbstring`, `xml`, `curl`, `ctype`, `filter` (Standard Laravel requirements)
*   **Node.js 18+** & NPM
*   **OpenSSL CLI** (Ensure it is accessible in your system PATH)

> [!NOTE]
> Default PHP installations on Windows (XAMPP/WAMP), Mac (Homebrew), and Linux (apt/yum) often vary. Please ensure the extensions above are enabled in your `php.ini`.

### 2. Setup

#### Option A: Terminal Access
```bash
# Clone and enter
git clone <your-repo-url>
cd app-tail

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate
```

#### Option B: Manual (No Terminal/Shared Hosting)
1.  **Download**: Click the "Code" button on GitHub and select **Download ZIP**, then extract it to your local computer.
2.  **Dependencies**:
    *   Run `composer install` and `npm run build` on your **local computer**.
    *   Upload the entire project folder to your server via FTP/File Manager, **including** the `vendor` and `public/build` directories.
3.  **Environment**:
    *   Rename `.env.example` to `.env` using your hosting File Manager.
    *   **APP_KEY**: Since you cannot run `key:generate`, visit `yourdomain.com/key-gen.html` to generate a secure key, then paste it into the `APP_KEY=` field in your `.env`.

### 3. Database & Migrations

#### Option A: Terminal Access (Recommended)
```bash
php artisan migrate --seed
```

#### Option B: Manual Import (Shared Hosting)
If your hosting does not provide terminal access:
1.  Create a new database via your hosting panel (e.g., cPanel MySQL Wizard).
2.  Open **phpMyAdmin**.
3.  Select your database and go to the **Import** tab.
4.  Choose the file `database/install.sql` from this project and click **Go**.
    *   **Default Admin**: `admin@dyzulk.com`
    *   **Default Password**: `password`

## 🚀 Production Deployment

### 1. Optimize Environment
Update your `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 2. Assets & Storage Link

#### Terminal Method
```bash
npm run build
php artisan storage:link
php artisan optimize
```

#### Manual Method (No Terminal)
1.  **Assets**: Ensure you have uploaded the `public/build` folder from your local machine after running `npm run build`.
2.  **Storage Link**: Create a file named `link.php` in your `public/` directory with this content:
    ```php
    <?php
    symlink(__DIR__.'/../storage/app/public', __DIR__.'/storage');
    echo "Storage link created!";
    ```
    Visit `yourdomain.com/link.php` in your browser, then delete the file.
3.  **Optimization**: To clear cache manually, delete all files inside `storage/framework/views/` and `bootstrap/cache/` (except `.gitignore`).

> [!IMPORTANT]
> **Web Server Root**: Ensure your domain/subdomain points to the `/public` directory of this project, not the root folder.

## 📡 API Endpoints

### Public CA Certificates
`GET /api/public/ca-certificates`
Returns Root and Intermediate CA certificates in JSON format.

### Authenticated Certificates
`GET /api/v1/certificates`
Retrieves user-specific leaf certificates. Requires `X-API-KEY` header.

## 📦 License
Refer to the [LICENSE](LICENSE) file for details.
