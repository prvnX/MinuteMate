# 📋 Minute Mate

## 🔐 Demo Credentials

Use the following credentials to log in to the system for demo/testing purposes:

### Secretary  
- **Username**: `lec001`  
- **Password**: `MyPassword123`  

### Board Member  
- **Username**: `lec001`  
- **Password**: `MyPassword123`  

### Admin  
- **Username**: `adm001`  
- **Password**: `MyPassword123`  

### Student Representative  
- **Username**: `stdrep002`  
- **Password**: `MyPassword123`  

---

## 📥 Database Download

You can download the database using the following link:  
🔗 [Click here to download the DB](https://drive.google.com/file/d/1Zi9PmhczXERhRonPS2ycnCZ5TFBYoguo/view?usp=sharing)

---

## 🛠 Installation & Setup

### Step 1: Install Composer

Make sure [Composer](https://getcomposer.org/) is installed on your system.

### Step 2: Install Dependencies

Run the following command in the project root directory:

```bash
composer install


```
Step 3: File & Folder Permissions
	•	Grant full access to the tmp/ folder. This is used to temporarily store ZIP files before sending them to the browser.
	•	Grant full permissions to the private.key file inside the keys/ directory. This key is used for document signing.

 Step 4: Environment Configuration

Create a .env file in the root directory with the following:
```bash
PASSPHRASE=your_passphrase_here
APIKEY=your_gemini_api_key_here

```

The private.key file must be encrypted with AES using the PASSPHRASE specified above.

⚙️ Additional Configuration
	•	Cloudinary Upload Model: Replace the configuration inside the Cloudinary model with your own API credentials.
	•	Mail Model: Replace the app_password and email fields with your actual email and app password.


 📦 Included Libraries
	•	PHPMailer – For sending emails via PHP
	•	Dompdf – To generate PDFs (e.g., meeting miutes) before applying digital signatures
	•	phpdotenv – For secure handling of environment variables via .env files

📌 Notes
	•	Ensure your PHP version is compatible with the included libraries.
	•	Email functionality and PDF generation depend on proper .env configuration.


