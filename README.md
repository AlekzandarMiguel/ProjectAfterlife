# PROJECT AFTERLIFE
### Web-Based Abandoned Software Project Recovery and Ownership Transfer System

A full-stack, enterprise-grade Laravel web platform designed to facilitate the structured submission, administrator verification, community adoption, recovery tracking, and certified resurrection of abandoned software projects.

---

## 🌟 Overview & System Purpose

Thousands of capable software systems, libraries, developer tools, and prototypes are abandoned each year due to lack of maintainer time, changes in career, or loss of funding. **Project Afterlife** provides a formal, auditable platform where:

1. **Original Creators** can upload abandoned projects, attach source archives & SQL dumps, and confirm legal ownership transfer rights.
2. **Platform Administrators** inspect incoming software, verify security requirements, review adoption proposals, and execute atomic ownership transfers.
3. **Developers / Adopters** apply to adopt software with explicit recovery roadmaps, track live dynamic recovery progress, manage milestone checklists, release versions, and submit for official resurrection certification.

---

## 🚀 Key Architectural Highlights

- **Strict Relational RBAC:** 2 System roles (`ADMIN`, `USER`) with policy-driven route and resource isolation.
- **Zero AI Dependency:** 100% human-verified validation, deterministic algorithms, and strict mathematical calculations.
- **Dynamic Progress Engine:** Progress % is strictly computed in real time:
  $$\text{Progress \%} = \left(\frac{\text{Completed Tasks}}{\text{Total Tasks}}\right) \times 100$$
- **Atomic Ownership Transfers:** Uses `DB::transaction()` with pessimistic row locks to safely swap project owners, record immutable ledger history, and preserve original uploader attribution forever.
- **Secure File Storage:** Tokenized downloads stored in non-executable protected directories (`local` disk), blocking any direct script execution.
- **Tamper-Evident Auditing:** Comprehensive `audit_logs` and `project_history` tables recording every lifecycle status transition and administrative action.

---

## 🛠️ Technology Stack

- **Backend:** Laravel 11 / PHP 8.2 (Strict typing, Form Requests, Services, Policies, Enums)
- **Frontend:** Blade, Tailwind CSS (@tailwindcss/vite), Alpine.js, Responsive Sidebar Navigation
- **Database:** SQLite / MySQL Parity (17 normalized relational migrations)
- **Asset Pipeline:** Vite 7

---

## 👥 Demo Test Accounts

All accounts use password: `password`

| Role | Name | Email | Purpose |
| :--- | :--- | :--- | :--- |
| **ADMIN** | Alexander Sterling | `admin@afterlife.dev` | Platform moderation, verification, approvals, resurrection certification |
| **USER** | Elena Rostova | `elena@afterlife.dev` | Project creator & adopter |
| **USER** | Devon Vance | `devon@afterlife.dev` | Active resurrector (QuantumQL) |
| **USER** | Marcus Brody | `marcus@afterlife.dev` | Active recovery owner (HyperLog Gateway) |
| **USER** | Sophia Lin | `sophia@afterlife.dev` | Developer & adopter |
| **USER** | Lucas Silva | `lucas@afterlife.dev` | Developer & contributor |
| **USER** | Aisha Patel | `aisha@afterlife.dev` | Developer & contributor |

---

## 🔄 The 5-Step Project Lifecycle

```
[1. PENDING_REVIEW]  -->  (Admin Approves)  -->  [2. AVAILABLE]
                                                       |
                                            (Developer Applies)
                                                       |
                                                       v
                                            [3. ADOPTION_PENDING]
                                                       |
                                            (Admin Approves Transfer)
                                                       |
                                                       v
                                            [4. UNDER_RECOVERY]
                                                       |
                                            (Checklist Progress to 100%)
                                            (Submit Final Review)
                                                       |
                                                       v
                                            [5. PENDING_FINAL_REVIEW]
                                                       |
                                            (Admin Certifies)
                                                       |
                                                       v
                                            [🏆 RESURRECTED]
```

---

## 💻 Local Setup & Execution

1. **Navigate to Project:**
   ```bash
   cd C:\Users\alekz\OneDrive\Desktop\Laravel\project-afterlife
   ```

2. **Install PHP & Node Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Build Frontend Assets:**
   ```bash
   npm run build
   ```

4. **Initialize Database & Seed Rich Demo Data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Run Automated Test Suite:**
   ```bash
   php artisan test
   ```

6. **Start Local Development Server:**
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.
