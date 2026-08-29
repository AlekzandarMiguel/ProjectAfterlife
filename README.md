# Project Afterlife
## Web-Based Abandoned Software Project Recovery and Ownership Transfer System

Project Afterlife is a full-stack, enterprise-grade web platform developed with Laravel. It provides a formal, secure, and auditable ecosystem for archiving, adopting, modernizing, and certifying abandoned software projects.

---

## 1. System Overview

Thousands of functional software applications, libraries, developer tools, and prototypes are abandoned each year due to career transitions, lack of maintainer bandwidth, or funding termination. Without a dedicated governance platform, these projects become obsolete, vulnerable, or lost.

Project Afterlife establishes an end-to-end framework where:
1. **Original Creators** submit abandoned projects, attach verified source archives, and legally authorize community adoption.
2. **Platform Administrators** inspect intake submissions, verify developer credentials, evaluate adoption applications, and certify resurrected software.
3. **Adopting Developers** apply for project custodianship with concrete recovery roadmaps, execute milestone tasks in dedicated workspaces, publish new versions, and bring abandoned software back to operational health.

---

## 2. Core Modules and Capabilities

### A. Public Discovery and Catalog
- **Public Explorer:** Searchable and filterable catalog of abandoned and adoptable software categorized by tech stack, difficulty, and domain.
- **Resurrected Hall of Fame:** Permanent public showcase celebrating successfully restored software, crediting both original uploaders and resurrecting maintainers.
- **Project Detail Profiles:** Comprehensive project overviews detailing abandonment reasons, architecture, required modernization tasks, versions, and verified source files.

### B. Project Submission and Custodianship Intake
- **Source Code and Asset Uploads:** Secure storage of archive packages (.zip, .tar, .gz) and documentation in protected private storage.
- **Ownership Declaration:** Explicit confirmation of ownership rights and legal transfer authorization before entering moderation.
- **Intake Review Queue:** Administrative review pipeline to validate source authenticity, scan for policy compliance, and publish to the public directory.

### C. Community Adoption and Atomic Transfer Engine
- **Formal Adoption Applications:** Developers submit structured recovery plans, modernization proposals, and estimated timelines.
- **Atomic Transfer Execution:** Database transaction engine utilizing row-level pessimistic locking (`lockForUpdate`) to safely transition project ownership while preserving immutable uploader attribution.
- **Provenance Ledger:** Permanent historical log (`project_history` and `ownership_transfers`) recording every custodial change with administrator signatures.

### D. Recovery Workspace and Progress Engine
- **Dynamic Progress Calculation:** Deterministic, real-time recovery completion metric calculated mathematically from milestone checklist tasks:
  Progress Percentage = (Completed Tasks / Total Tasks) * 100
- **Milestone Management:** Workspace task lists categorized by phase (Bug Fixes, Refactoring, Documentation, Testing, Deployment).
- **Version Release Management:** Adopters publish semantically versioned releases (e.g., v1.0.0, v2.0.0) with changelogs and build archives.
- **Resurrection Certification Submission:** Adopters submit completed recoveries for formal administrative inspection once all checklist milestones reach 100%.

### E. Security, Authentication, and Governance
- **Mandatory Administrator Approval for New Users:** All new user registrations default to a pending verification status until vetted by administrators.
- **Real-Time Verification Screen:** The waiting interface automatically detects and transitions to an approved state the instant an administrator authorizes the account.
- **6-Digit Email OTP Password Reset:** Secure, time-limited (15-minute) numeric code verification sent via SMTP for password recovery.
- **Role-Based Access Control (RBAC):** Server-side authorization enforced via custom middleware, Gates, and granular Model Policies across two roles: Administrator and User.
- **Administrator Provisioning:** Administrators can directly create and configure both Administrator and Developer accounts from the management console.
- **Zero-Trust File Security:** Uploaded files receive internal UUID filenames and are stored outside the public web root with tokenized access authorization.
- **Tamper-Evident Auditing:** Comprehensive audit trail recording all security-sensitive events, logins, role assignments, and ownership transitions.
- **HTTP Security Headers:** Hardened response headers including X-Frame-Options, X-Content-Type-Options, Referrer-Policy, and Permissions-Policy.

---

## 3. Project Lifecycle State Machine

Projects progress through a deterministic, strictly validated state machine:

```
[1. PENDING_REVIEW]
        |
   (Admin Approves Intake)
        v
[2. AVAILABLE]
        |
   (Developer Submits Adoption Request)
        v
[3. ADOPTION_PENDING]
        |
   (Admin Approves Transfer)
        v
[4. UNDER_RECOVERY]
        |
   (Adopter Completes All Checklist Tasks to 100%)
   (Adopter Submits for Final Review)
        v
[5. PENDING_FINAL_REVIEW]
        |
   (Admin Inspects and Certifies)
        v
[6. RESURRECTED]
```

---

## 4. User Roles and Authorization Matrix

| Feature / Resource | Guest | Developer (User) | Administrator (Admin) |
| :--- | :--- | :--- | :--- |
| Browse Public Projects and Resurrected Showcase | Yes | Yes | Yes |
| Download Public Project Files | No | Yes (Authenticated) | Yes |
| Submit New Abandoned Project | No | Yes | Yes |
| Apply for Project Adoption | No | Yes (Approved Only) | Yes |
| Manage Recovery Workspace and Tasks | No | Yes (Owner Only) | Yes (Supervisory) |
| Publish Project Versions | No | Yes (Owner Only) | Yes (Supervisory) |
| Submit Project for Final Resurrection Review | No | Yes (Owner Only) | Yes |
| Approve / Reject Adoption Requests | No | No | Yes |
| Approve / Reject Project Submissions | No | No | Yes |
| Approve / Certify Resurrections | No | No | Yes |
| Manage Users and Approve Registrations | No | No | Yes |
| Create New User and Admin Accounts | No | No | Yes |
| View System Audit Logs and Metrics | No | No | Yes |

---

## 5. Technology Stack

- **Backend Framework:** Laravel 11 / PHP 8.2+
- **Frontend Architecture:** Blade Templates, Tailwind CSS, Alpine.js
- **Database Engine:** SQLite (Local Development) / MySQL / PostgreSQL (Production Parity)
- **Asset Bundler:** Vite 7
- **Mail Delivery:** SMTP (Gmail / Brevo / Standard Mail Transfer Agents) with Blade HTML templates
- **Code Quality & Testing:** PHPUnit, Larastan / PHPStan (Level 5 static analysis)

---

## 6. Installation and Setup Guide

### Prerequisites
- PHP >= 8.2 with PDO, OpenSSL, Mbstring, and Tokenizer extensions
- Composer >= 2.x
- Node.js >= 18.x and npm
- Git

### Step-by-Step Installation

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/AlekzandarMiguel/ProjectAfterlife.git
   cd ProjectAfterlife
   ```

2. **Install Backend Dependencies:**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies and Build Assets:**
   ```bash
   npm install
   npm run build
   ```

4. **Configure Environment File:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Mail Settings (in .env):**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_ENCRYPTION=tls
   MAIL_USERNAME=your_email@example.com
   MAIL_PASSWORD=your_app_password
   MAIL_FROM_ADDRESS="your_email@example.com"
   MAIL_FROM_NAME="Project Afterlife"
   ```

6. **Initialize Database and Seed Demo Data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Start the Application Server:**
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your web browser.

---

## 7. Demo User Credentials

The database seeder provisions demo accounts for testing all system workflows. All demo accounts use the standard password: `Password123!` (or `password`).

| Role | Name | Email | Primary Responsibilities |
| :--- | :--- | :--- | :--- |
| Administrator | Alexander Sterling | `admin@afterlife.dev` | Platform moderation, verification, approvals, resurrection certification |
| Developer | Elena Rostova | `elena@afterlife.dev` | Project creator and adopter |
| Developer | Devon Vance | `devon@afterlife.dev` | Active resurrector (QuantumQL) |
| Developer | Marcus Brody | `marcus@afterlife.dev` | Active recovery owner (HyperLog Gateway) |
| Developer | Sophia Lin | `sophia@afterlife.dev` | Developer and adopter |
| Developer | Lucas Silva | `lucas@afterlife.dev` | Developer and contributor |
| Developer | Aisha Patel | `aisha@afterlife.dev` | Developer and contributor |

---

## 8. Verification and Testing Suite

The application includes an extensive automated test suite covering authentication, role-based authorization, rate limiting, file upload security, atomic ownership transfer, and vulnerability assessments.

Run the test suite:
```bash
php artisan test
```

Execute static code analysis:
```bash
vendor/bin/phpstan analyse --no-progress
```

---

## 9. License

This project is open-source software licensed under the MIT License.
