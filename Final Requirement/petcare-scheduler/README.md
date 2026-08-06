# PetCare Scheduler
### A UML-Driven Veterinary Appointment System

PetCare Scheduler is a web-based veterinary appointment management system designed to simplify appointment scheduling for pet owners while providing veterinarians with an efficient dashboard to manage consultations. The system replaces manual appointment books with a secure, database-driven platform that improves workflow and reduces scheduling conflicts. :contentReference[oaicite:0]{index=0}

---

##  Features

### Pet Owner
- User Registration and Login
- Manage Pet Profiles
- Book Veterinary Appointments
- View Appointment Status
- Cancel Appointments

### Veterinarian
- Secure Login
- View Appointment Queue
- Approve and Manage Appointments
- Update Appointment Status
- Delete Cancelled Records

---

##  Technologies Used

### Frontend
- HTML5
- CSS3
- JavaScript (ES6)
- Font Awesome

### Backend
- PHP
- Laravel Framework

### Database
- MySQL

### Development Tools
- Visual Studio Code
- Laravel Artisan CLI
- Browser Developer Tools :contentReference[oaicite:1]{index=1}

---

##  System Modules

- Authentication System
- User Management
- Pet Management
- Appointment Scheduling
- Veterinary Dashboard
- Appointment Status Tracking
- Database Management

---

##  User Roles

### Pet Owner
- Register an account
- Manage multiple pet profiles
- Book appointments
- Cancel appointments
- View appointment history

### Veterinarian
- Monitor appointment requests
- Claim appointments
- Approve or reject bookings
- Manage appointment records
- Update appointment status :contentReference[oaicite:2]{index=2}

---

##  Database Structure

The system consists of four main tables:

- **Users**
- **Pets**
- **Veterinarians**
- **Appointments**

Relationships:

```
User
 └── owns many Pets

Pet
 └── has many Appointments

Veterinarian
 └── handles many Appointments
```

---

##  UML Diagrams

The project documentation includes:

- Use Case Diagram
- System Flowchart
- UML Class Diagram
- System Architecture Diagram :contentReference[oaicite:3]{index=3}

---

##  Security Features

- Role-Based Authentication
- Laravel Middleware Protection
- Session Authentication
- Credential Verification
- MySQL Foreign Key Constraints
- Secure Fetch Requests
- Protected Veterinary Dashboard :contentReference[oaicite:4]{index=4}

---

##  Installation

### Clone the repository

```bash
git clone https://github.com/yourusername/petcare-scheduler.git
```

### Navigate into the project

```bash
cd petcare-scheduler
```

### Install dependencies

```bash
composer install
npm install
```

### Configure environment

```bash
cp .env.example .env
```

Update the database configuration inside the `.env` file.

### Generate application key

```bash
php artisan key:generate
```

### Run migrations

```bash
php artisan migrate
```

### Start the development server

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```

---

##  Project Structure

```
PetCare-Scheduler
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── .env
├── composer.json
└── README.md
```

---

##  Objectives

- Replace manual veterinary appointment scheduling.
- Improve clinic workflow efficiency.
- Prevent appointment conflicts.
- Provide secure role-based access.
- Maintain organized pet and appointment records. :contentReference[oaicite:5]{index=5}

---

## 📈 Future Improvements

- Email and SMS Notifications
- Appointment Conflict Detection
- Medical History Records
- Inventory Management
- Health Statistics Dashboard
- Modular System Architecture :contentReference[oaicite:6]{index=6}

---

##  Documentation

This project includes complete documentation containing:

- Introduction
- Objectives
- Scope and Limitations
- System Description
- Technologies Used
- UML Diagrams
- Database Design
- Implementation and Testing
- Interface Screenshots
- Conclusion
- Recommendations

---

##  Developer

**Raineer P. Tamiat**

BS Information Technology

System Integration and Architecture 2 :contentReference[oaicite:7]{index=7}

---

##  License

This project is developed for educational purposes and academic requirements.
