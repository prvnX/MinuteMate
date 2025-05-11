📋 Minute Mate
Minute Mate is a smart meeting management system designed for academic institutions. It enables streamlined handling of meeting minutes, attendance tracking, document signing, and communication between stakeholders such as Secretaries, Lecturers, Admins, and Student Representatives.

🔐 Demo Credentials
Use the following credentials to log in to the system for demo/testing purposes:

Secretary
Username: sec001

Password: MyPassword123

Lecturer
Username: lec001

Password: MyPassword123

Admin
Username: adm001

Password: MyPassword123

Student Representative
Username: stdrep001

Password: MyPassword123

📥 Database Download
You can download the database using the following link:
🔗 Click here to download the DB

🛠 Installation & Setup
Step 1: Install Composer
Make sure Composer is installed on your system.

Step 2: Install Dependencies
Run the following command in the project root directory to install the required PHP dependencies:

composer install
Step 3: File & Folder Permissions
Grant full access permissions to the tmp/ folder. This folder is used to temporarily store ZIP files before sending them to the browser.

Grant full permissions to the private.key file located inside the keys/ directory. This key is used for signing documents.

Step 4: Environment Configuration
Create a .env file in the root directory with the following variables:

dotenv

PASSPHRASE=your_passphrase_here
APIKEY=your_gemini_api_key_here
The private.key file inside the keys/ directory must be encrypted with AES using the PASSPHRASE specified in your .env file.

📦 Included Libraries
PHPMailer: Used for sending emails via PHP

Dompdf: Used to generate PDFs (e.g., meeting minutes) before applying digital signatures

phpdotenv: Loads environment variables from the .env file securely

📌 Notes
Ensure your PHP version is compatible with the libraries used.

Email functionality and PDF generation require proper configuration of the .env file.

This system is intended for use in local or development environments unless production-ready security is applied.

