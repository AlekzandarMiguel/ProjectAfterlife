<?php

namespace Database\Seeders;

use App\Enums\AdoptionStatus;
use App\Enums\DevelopmentStatus;
use App\Enums\FileType;
use App\Enums\FinalReviewStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Enums\TaskPhase;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\AdoptionRequest;
use App\Models\AuditLog;
use App\Models\Category;
use App\Models\FinalReviewSubmission;
use App\Models\OwnershipDeclaration;
use App\Models\OwnershipTransfer;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\ProjectHistory;
use App\Models\ProjectVersion;
use App\Models\RecoveryTask;
use App\Models\RecoveryUpdate;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $elena = User::where('email', 'elena@afterlife.dev')->first();
        $marcus = User::where('email', 'marcus@afterlife.dev')->first();
        $sarah = User::where('email', 'sarah@afterlife.dev')->first();
        $devon = User::where('email', 'devon@afterlife.dev')->first();
        $kaito = User::where('email', 'kaito@afterlife.dev')->first();
        $amira = User::where('email', 'amira@afterlife.dev')->first();

        $catWeb = Category::where('slug', 'web-application')->first();
        $catMobile = Category::where('slug', 'mobile-applications')->first();
        $catCli = Category::where('slug', 'developer-tool-cli')->first();
        $catApi = Category::where('slug', 'backend-api-services')->first();
        $catSec = Category::where('slug', 'security-devsecops')->first();
        $catLib = Category::where('slug', 'libraries-packages')->first();

        $techPhp = Technology::where('slug', 'php')->first();
        $techLaravel = Technology::where('slug', 'laravel')->first();
        $techMysql = Technology::where('slug', 'mysql')->first();
        $techVue = Technology::where('slug', 'vue-js')->first();
        $techTailwind = Technology::where('slug', 'tailwind-css')->first();
        $techGo = Technology::where('slug', 'go')->first();
        $techPostgres = Technology::where('slug', 'postgresql')->first();
        $techDocker = Technology::where('slug', 'docker')->first();
        $techFlutter = Technology::where('slug', 'flutter')->first();
        $techDart = Technology::where('slug', 'dart')->first();
        $techPython = Technology::where('slug', 'python')->first();
        $techFastApi = Technology::where('slug', 'fastapi')->first();
        $techRust = Technology::where('slug', 'rust')->first();
        $techTs = Technology::where('slug', 'typescript')->first();

        // -------------------------------------------------------------
        // PROJECT 1: RESURRECTED SHOWCASE (Vaultwarden Sync CLI)
        // Original Owner: Devon -> Transferred to: Elena -> RESURRECTED
        // -------------------------------------------------------------
        $p1 = Project::updateOrCreate(
            ['slug' => 'vault-sync-cli-tool'],
            [
                'owner_id' => $elena->id, // Resurrector / Current Owner
                'original_owner_id' => $devon->id, // Original Author
                'category_id' => $catCli->id,
                'title' => 'VaultSync: Encrypted Secret Backup CLI',
                'short_description' => 'A lightning-fast command-line utility for automated AES-256 encrypted backups of self-hosted credential vaults.',
                'description' => "VaultSync was initially designed as an automated disaster-recovery backup tool for developer vaults. Devon built the prototype in Go but had to abandon it in 2024 due to full-time startup commitments.\n\nElena adopted the project in mid-2025, completely overhauled the cryptographic verification pipeline, implemented S3/GCS multipart encrypted uploads, added cross-platform build pipelines, and wrote 95% unit test coverage.\n\nThe project is now fully recovered, validated by the administrator, and marked as RESURRECTED.",
                'project_type' => ProjectType::CLI,
                'development_status' => DevelopmentStatus::PROTOTYPE,
                'reason_for_abandonment' => 'Ran out of spare time after moving to a new tech lead role; couldn\'t finish key rotation module.',
                'original_development_date' => '2023-05-10',
                'last_development_date' => '2024-01-15',
                'status' => ProjectStatus::RESURRECTED,
                'is_featured' => true,
                'published_at' => now()->subMonths(6),
                'resurrected_at' => now()->subDays(5),
                'last_activity_at' => now()->subDays(5),
            ]
        );
        $p1->technologies()->sync([$techGo->id, $techDocker->id, $techPostgres->id]);

        // Versions for P1
        $v1_0 = ProjectVersion::create([
            'project_id' => $p1->id,
            'uploaded_by' => $devon->id,
            'version_number' => 'v0.5.0-alpha',
            'title' => 'Original Abandoned Prototype',
            'description' => 'Initial code base before abandonment.',
            'release_notes' => 'Basic CLI argument parser and initial AES encryption module.',
            'created_at' => now()->subMonths(6),
        ]);

        $v2_0 = ProjectVersion::create([
            'project_id' => $p1->id,
            'uploaded_by' => $elena->id,
            'version_number' => 'v1.0.0',
            'title' => 'Resurrected Production Release',
            'description' => 'Full recovery release with complete S3 upload and cryptographic key rotation.',
            'release_notes' => "1. Refactored crypto engine to use AES-256-GCM\n2. Added automated Cron scheduling\n3. Cloudflare R2 and AWS S3 connectors\n4. 95% test coverage",
            'is_final_release' => true,
            'created_at' => now()->subDays(5),
        ]);

        // Mock Files
        ProjectFile::create([
            'project_id' => $p1->id,
            'version_id' => $v2_0->id,
            'uploaded_by' => $elena->id,
            'file_name' => 'vaultsync-v1.0.0-source.zip',
            'storage_path' => 'projects/' . $p1->id . '/files/vaultsync-v1.0.0.zip',
            'file_type' => FileType::SOURCE_CODE_ZIP,
            'file_size' => 1048576 * 4,
            'mime_type' => 'application/zip',
            'is_current' => true,
        ]);

        // Ownership Declaration
        OwnershipDeclaration::create([
            'project_id' => $p1->id,
            'user_id' => $devon->id,
            'declaration_text' => 'I confirm that I have the right to submit VaultSync to Project Afterlife.',
            'ip_address' => '127.0.0.1',
            'confirmed_at' => now()->subMonths(6),
        ]);

        // Adoption Request
        $ad1 = AdoptionRequest::create([
            'project_id' => $p1->id,
            'user_id' => $elena->id,
            'reason' => 'I actively use self-hosted secrets in my daily infrastructure work and noticed this project had great foundations but lacked key rotation and cloud connectors.',
            'proposed_improvements' => 'Implement AES-GCM streaming encryption, write automated GitHub actions for multi-arch compilation, and add S3 destination support.',
            'recovery_plan' => "Phase 1: Code audit\nPhase 2: Modernize Go dependencies\nPhase 3: Implement S3 streaming\nPhase 4: Comprehensive test suite",
            'expected_completion_date' => now()->addMonths(2),
            'relevant_skills' => '7 years of Go microservices experience, cryptographic systems background.',
            'status' => AdoptionStatus::APPROVED,
            'admin_notes' => 'Exceptional recovery proposal. Highly qualified developer.',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subMonths(4),
            'created_at' => now()->subMonths(4),
        ]);

        // Ownership Transfer Record
        OwnershipTransfer::create([
            'project_id' => $p1->id,
            'previous_owner_id' => $devon->id,
            'new_owner_id' => $elena->id,
            'adoption_request_id' => $ad1->id,
            'approved_by' => $admin->id,
            'transfer_reason' => 'Approved adoption request with thorough recovery roadmap.',
            'transfer_status' => 'completed',
            'transferred_at' => now()->subMonths(4),
        ]);

        // Recovery Tasks (All Completed for 100% progress)
        $tasks1 = [
            ['title' => 'Audit legacy Go dependencies & fix vulnerability alerts', 'phase' => TaskPhase::ASSESSMENT, 'prio' => TaskPriority::HIGH],
            ['title' => 'Refactor crypto module to use standard AES-256-GCM', 'phase' => TaskPhase::REPAIR, 'prio' => TaskPriority::URGENT],
            ['title' => 'Implement AWS S3 & Cloudflare R2 backup targets', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::HIGH],
            ['title' => 'Write end-to-end integration tests and unit mocks', 'phase' => TaskPhase::TESTING, 'prio' => TaskPriority::MEDIUM],
            ['title' => 'Setup multi-architecture release workflow for Linux/macOS/Windows', 'phase' => TaskPhase::DEPLOYMENT, 'prio' => TaskPriority::MEDIUM],
        ];

        foreach ($tasks1 as $idx => $t) {
            RecoveryTask::create([
                'project_id' => $p1->id,
                'assigned_to' => $elena->id,
                'title' => $t['title'],
                'phase' => $t['phase'],
                'priority' => $t['prio'],
                'status' => TaskStatus::COMPLETED,
                'due_date' => now()->subMonths(2),
                'completed_at' => now()->subMonths(1),
                'order_index' => $idx + 1,
            ]);
        }

        // Final Review Submission
        FinalReviewSubmission::create([
            'project_id' => $p1->id,
            'version_id' => $v2_0->id,
            'submitted_by' => $elena->id,
            'completion_summary' => 'All planned recovery phases are complete. VaultSync now has zero vulnerabilities, full streaming backup support, and 95% test coverage.',
            'completed_features' => '- AES-256-GCM encryption pipeline\n- S3/R2/GCS cloud upload\n- Cross-platform CLI binaries\n- Full documentation',
            'testing_summary' => 'Ran full test suite on Linux AMD64, ARM64, Darwin M-series, and Windows. 124 unit and feature tests passing.',
            'status' => FinalReviewStatus::APPROVED,
            'admin_feedback' => 'Incredible recovery effort! Approved for official Resurrection.',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(5),
            'created_at' => now()->subDays(7),
        ]);

        // History
        ProjectHistory::create(['project_id' => $p1->id, 'user_id' => $devon->id, 'action' => 'SUBMITTED', 'old_status' => null, 'new_status' => 'PENDING_REVIEW', 'description' => 'Project submitted for verification.']);
        ProjectHistory::create(['project_id' => $p1->id, 'user_id' => $admin->id, 'action' => 'APPROVED', 'old_status' => 'PENDING_REVIEW', 'new_status' => 'AVAILABLE', 'description' => 'Project verified and published.']);
        ProjectHistory::create(['project_id' => $p1->id, 'user_id' => $elena->id, 'action' => 'ADOPTION_REQUESTED', 'old_status' => 'AVAILABLE', 'new_status' => 'ADOPTION_PENDING', 'description' => 'Elena submitted adoption proposal.']);
        ProjectHistory::create(['project_id' => $p1->id, 'user_id' => $admin->id, 'action' => 'OWNERSHIP_TRANSFERRED', 'old_status' => 'ADOPTION_PENDING', 'new_status' => 'UNDER_RECOVERY', 'description' => "Ownership transferred from Devon to Elena."]);
        ProjectHistory::create(['project_id' => $p1->id, 'user_id' => $admin->id, 'action' => 'PROJECT_RESURRECTED', 'old_status' => 'PENDING_FINAL_REVIEW', 'new_status' => 'RESURRECTED', 'description' => "Project certified as RESURRECTED."]);

        // -------------------------------------------------------------
        // PROJECT 2: UNDER RECOVERY (HealthTrack Telemedicine PWA)
        // Original Owner: Sarah -> Transferred to: Devon -> UNDER_RECOVERY (60% Progress)
        // -------------------------------------------------------------
        $p2 = Project::updateOrCreate(
            ['slug' => 'healthtrack-telemedicine-pwa'],
            [
                'owner_id' => $devon->id, // New Owner
                'original_owner_id' => $sarah->id, // Original
                'category_id' => $catWeb->id,
                'title' => 'HealthTrack: Open Telehealth Patient Portal',
                'short_description' => 'A lightweight, HIPAA-friendly patient appointment scheduling and prescription management web application.',
                'description' => "HealthTrack was built for a rural community health initiative that lost its grant funding before the portal could be finalized. It features WebRTC video appointments, real-time messaging, and patient record management in Laravel and Vue.js.\n\nDevon adopted this project to convert it into a generic open-source telehealth boilerplate for clinics.",
                'project_type' => ProjectType::WEB,
                'development_status' => DevelopmentStatus::BETA,
                'reason_for_abandonment' => 'Client lost NGO funding; couldn\'t afford ongoing development or hosting.',
                'original_development_date' => '2023-08-01',
                'last_development_date' => '2024-03-20',
                'status' => ProjectStatus::UNDER_RECOVERY,
                'is_featured' => true,
                'published_at' => now()->subMonths(3),
                'last_activity_at' => now()->subDays(2),
            ]
        );
        $p2->technologies()->sync([$techLaravel->id, $techVue->id, $techMysql->id, $techTailwind->id]);

        $v2_1 = ProjectVersion::create([
            'project_id' => $p2->id,
            'uploaded_by' => $sarah->id,
            'version_number' => 'v0.8.0',
            'title' => 'Original Beta Build',
            'description' => 'Incomplete patient portal with WebRTC mockups.',
            'release_notes' => 'Initial Laravel/Vue codebase.',
            'created_at' => now()->subMonths(3),
        ]);

        $v2_2 = ProjectVersion::create([
            'project_id' => $p2->id,
            'uploaded_by' => $devon->id,
            'version_number' => 'v0.9.0-recovery',
            'title' => 'Auth Refactor & Laravel 11 Upgrade',
            'description' => 'Upgraded dependencies, modernized authentication with Sanctum, fixed database schema.',
            'release_notes' => "1. Upgraded to Laravel 11\n2. Fixed SQL security holes in appointment queries\n3. Responsive Tailwind UI refactor",
            'created_at' => now()->subDays(10),
        ]);

        $ad2 = AdoptionRequest::create([
            'project_id' => $p2->id,
            'user_id' => $devon->id,
            'reason' => 'I have 9 years of Laravel experience and have previously built healthcare portals. This project has solid architecture that deserves to be finished.',
            'proposed_improvements' => 'Upgrade to modern Laravel, add clean doctor scheduling calendar, and sanitize patient notes.',
            'recovery_plan' => "1. Upgrade Laravel stack\n2. Doctor calendar integration\n3. WebRTC video consult module\n4. HIPAA compliance audit",
            'expected_completion_date' => now()->addMonths(1),
            'relevant_skills' => 'Expert PHP/Laravel developer.',
            'status' => AdoptionStatus::APPROVED,
            'admin_notes' => 'Approved. Devon is an experienced Laravel engineer.',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subMonths(1),
            'created_at' => now()->subMonths(1),
        ]);

        OwnershipTransfer::create([
            'project_id' => $p2->id,
            'previous_owner_id' => $sarah->id,
            'new_owner_id' => $devon->id,
            'adoption_request_id' => $ad2->id,
            'approved_by' => $admin->id,
            'transfer_reason' => 'Approved adoption plan to upgrade and complete telehealth portal.',
            'transfer_status' => 'completed',
            'transferred_at' => now()->subMonths(1),
        ]);

        // Tasks for P2 (3 completed out of 5 = 60% progress)
        $tasks2 = [
            ['title' => 'Upgrade framework from Laravel 9 to Laravel 11 & PHP 8.2', 'phase' => TaskPhase::REPAIR, 'prio' => TaskPriority::URGENT, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Migrate Vue 2 frontend to Vue 3 Composition API with Vite', 'phase' => TaskPhase::REPAIR, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Implement interactive doctor calendar with booking slots', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Integrate Agora / Daily.co WebRTC video consultation room', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::IN_PROGRESS],
            ['title' => 'Write automated end-to-end appointment workflow tests', 'phase' => TaskPhase::TESTING, 'prio' => TaskPriority::MEDIUM, 'status' => TaskStatus::TODO],
        ];

        foreach ($tasks2 as $idx => $t) {
            RecoveryTask::create([
                'project_id' => $p2->id,
                'assigned_to' => $devon->id,
                'title' => $t['title'],
                'phase' => $t['phase'],
                'priority' => $t['prio'],
                'status' => $t['status'],
                'due_date' => now()->addWeeks(2),
                'completed_at' => $t['status'] === TaskStatus::COMPLETED ? now()->subDays(5) : null,
                'order_index' => $idx + 1,
            ]);
        }

        RecoveryUpdate::create([
            'project_id' => $p2->id,
            'user_id' => $devon->id,
            'update_title' => 'Vue 3 Migration and Framework Upgrade Complete',
            'update_text' => 'Successfully ported all 24 Vue components to Vue 3 script setup syntax. Replaced Webpack with Vite for instant HMR. Calendar scheduling module is now 100% functional.',
        ]);

        // -------------------------------------------------------------
        // PROJECT 3: AVAILABLE FOR ADOPTION (Rust Bytecode VM)
        // Owner: Kaito -> AVAILABLE
        // -------------------------------------------------------------
        $p3 = Project::updateOrCreate(
            ['slug' => 'rust-nano-vm-interpreter'],
            [
                'owner_id' => $kaito->id,
                'original_owner_id' => $kaito->id,
                'category_id' => $catLib->id,
                'title' => 'NanoVM: Embeddable Stack-Based Bytecode VM in Rust',
                'short_description' => 'A tiny, memory-safe register-and-stack virtual machine with a custom bytecode assembler designed for embedded games and scripting.',
                'description' => "NanoVM is a minimal bytecode execution engine written in Rust. It was created to provide a lightweight embedded scripting language for hobby game engines.\n\nThe parser, instruction set architecture, and base execution loop are working, but the garbage collector and foreign function interface (FFI) bindings remain unfinished.\n\nLooking for a systems/Rust developer to adopt this project and complete the JIT / FFI layer.",
                'project_type' => ProjectType::LIBRARY,
                'development_status' => DevelopmentStatus::PROTOTYPE,
                'reason_for_abandonment' => 'Switched degree focus to robotics and no longer have time to maintain the compiler toolchain.',
                'original_development_date' => '2024-02-10',
                'last_development_date' => '2024-09-01',
                'status' => ProjectStatus::AVAILABLE,
                'is_featured' => true,
                'published_at' => now()->subWeeks(3),
                'last_activity_at' => now()->subWeeks(3),
            ]
        );
        $p3->technologies()->sync([$techRust->id]);

        OwnershipDeclaration::create([
            'project_id' => $p3->id,
            'user_id' => $kaito->id,
            'declaration_text' => 'I confirm that I wrote NanoVM and have full rights to submit it to Project Afterlife.',
            'ip_address' => '127.0.0.1',
            'confirmed_at' => now()->subWeeks(3),
        ]);

        // -------------------------------------------------------------
        // PROJECT 4: AVAILABLE FOR ADOPTION (Flutter Habit Tracker)
        // Owner: Sarah -> AVAILABLE
        // -------------------------------------------------------------
        $p4 = Project::updateOrCreate(
            ['slug' => 'habitforge-flutter-app'],
            [
                'owner_id' => $sarah->id,
                'original_owner_id' => $sarah->id,
                'category_id' => $catMobile->id,
                'title' => 'HabitForge: Offline-First Minimalist Habit Tracker',
                'short_description' => 'An offline-first mobile application featuring streak heatmaps, local SQLite sync, and interactive widget notifications.',
                'description' => "HabitForge was created to provide a distraction-free, privacy-preserving habit tracker that requires zero cloud login. Built with Flutter, Provider state management, and SQLite.\n\nThe UI and local database are polished, but background alarm scheduling for Android 14+ and iOS badge synchronization were left incomplete.",
                'project_type' => ProjectType::MOBILE,
                'development_status' => DevelopmentStatus::BETA,
                'reason_for_abandonment' => 'Graduated and entered full-time agency employment; unable to maintain iOS/Android store updates.',
                'original_development_date' => '2023-11-05',
                'last_development_date' => '2024-05-18',
                'status' => ProjectStatus::AVAILABLE,
                'is_featured' => true,
                'published_at' => now()->subWeeks(2),
                'last_activity_at' => now()->subWeeks(2),
            ]
        );
        $p4->technologies()->sync([$techFlutter->id, $techDart->id, $techPostgres->id]);

        OwnershipDeclaration::create([
            'project_id' => $p4->id,
            'user_id' => $sarah->id,
            'declaration_text' => 'I confirm that HabitForge is my original open source code.',
            'ip_address' => '127.0.0.1',
            'confirmed_at' => now()->subWeeks(2),
        ]);

        // -------------------------------------------------------------
        // PROJECT 5: AVAILABLE FOR ADOPTION (FastAPI GeoJSON Indexer)
        // Owner: Amira -> AVAILABLE
        // -------------------------------------------------------------
        $p5 = Project::updateOrCreate(
            ['slug' => 'geoflux-spatial-api'],
            [
                'owner_id' => $amira->id,
                'original_owner_id' => $amira->id,
                'category_id' => $catApi->id,
                'title' => 'GeoFlux: High-Performance Spatial Query & GeoJSON API',
                'short_description' => 'FastAPI service with PostGIS spatial indexing for high-speed polygon intersection and bounding-box queries.',
                'description' => "GeoFlux provides blazing fast spatial polygon containment and nearest-neighbor lookups using Python, FastAPI, and PostGIS.\n\nNeeds a developer to add clustering algorithms and Docker Compose documentation.",
                'project_type' => ProjectType::API,
                'development_status' => DevelopmentStatus::PROTOTYPE,
                'reason_for_abandonment' => 'The client pivoted to a proprietary GIS solution.',
                'original_development_date' => '2024-01-12',
                'last_development_date' => '2024-06-30',
                'status' => ProjectStatus::AVAILABLE,
                'is_featured' => false,
                'published_at' => now()->subDays(10),
                'last_activity_at' => now()->subDays(10),
            ]
        );
        $p5->technologies()->sync([$techPython->id, $techFastApi->id, $techPostgres->id, $techDocker->id]);

        // -------------------------------------------------------------
        // PROJECT 6: ADOPTION_PENDING (DevSecOps Vulnerability Scanner)
        // Owner: Amira -> Applicant: Marcus -> ADOPTION_PENDING
        // -------------------------------------------------------------
        $p6 = Project::updateOrCreate(
            ['slug' => 'vulnscan-ci-container-auditor'],
            [
                'owner_id' => $amira->id,
                'original_owner_id' => $amira->id,
                'category_id' => $catSec->id,
                'title' => 'VulnScan: Lightweight CI Docker Security Auditor',
                'short_description' => 'Static analysis tool to inspect container Dockerfiles and base images for known CVEs and bad security practices in CI/CD pipelines.',
                'description' => "VulnScan parses Dockerfiles and lockfiles to detect insecure defaults, root execution permissions, and known CVEs.\n\nMarcus has applied to adopt this project to integrate GitHub Action workflows and SARIF output format.",
                'project_type' => ProjectType::CLI,
                'development_status' => DevelopmentStatus::ALPHA,
                'reason_for_abandonment' => 'Primary author transitioned to private penetration testing work.',
                'original_development_date' => '2024-03-01',
                'last_development_date' => '2024-07-15',
                'status' => ProjectStatus::ADOPTION_PENDING,
                'is_featured' => false,
                'published_at' => now()->subDays(20),
                'last_activity_at' => now()->subDays(1),
            ]
        );
        $p6->technologies()->sync([$techPython->id, $techDocker->id]);

        AdoptionRequest::create([
            'project_id' => $p6->id,
            'user_id' => $marcus->id,
            'reason' => 'I maintain multiple open source CI actions and would love to turn VulnScan into a standardized GitHub Action that outputs SARIF report format for GitHub Security tabs.',
            'proposed_improvements' => '1. Implement SARIF 2.1.0 output\n2. Add GitHub Action packaging\n3. Add support for Alpine APK and Debian DPKG vulnerability databases',
            'recovery_plan' => 'Phase 1: SARIF formatting (Week 1-2)\nPhase 2: Database cache (Week 3-4)\nPhase 3: CI/CD integration and test suite (Week 5)',
            'expected_completion_date' => now()->addMonths(2),
            'relevant_skills' => 'Python backend developer with extensive experience building GitHub Actions.',
            'status' => AdoptionStatus::PENDING,
            'created_at' => now()->subDays(1),
        ]);

        // -------------------------------------------------------------
        // PROJECT 7: PENDING_REVIEW (Fresh Submission from Marcus)
        // Owner: Marcus -> PENDING_REVIEW
        // -------------------------------------------------------------
        $p7 = Project::updateOrCreate(
            ['slug' => 'cronmaster-distributed-scheduler'],
            [
                'owner_id' => $marcus->id,
                'original_owner_id' => $marcus->id,
                'category_id' => $catApi->id,
                'title' => 'CronMaster: Distributed Redis-Backed Task Scheduler',
                'short_description' => 'A robust, distributed job orchestrator with leader election and real-time WebSocket execution telemetry in TypeScript.',
                'description' => "CronMaster is designed to execute recurring background tasks reliably across multiple clustered nodes without duplicate executions.\n\nIncludes a web-based dashboard and REST API for dynamic job registration.",
                'project_type' => ProjectType::API,
                'development_status' => DevelopmentStatus::ALPHA,
                'reason_for_abandonment' => 'Side project built during weekend hackathon; lacked time to write production docs and edge-case handling.',
                'original_development_date' => '2024-04-10',
                'last_development_date' => '2024-08-01',
                'status' => ProjectStatus::PENDING_REVIEW,
                'is_featured' => false,
                'last_activity_at' => now()->subHours(5),
            ]
        );
        $p7->technologies()->sync([$techTs->id, $techDocker->id]);

        OwnershipDeclaration::create([
            'project_id' => $p7->id,
            'user_id' => $marcus->id,
            'declaration_text' => 'I confirm that I have the right to submit CronMaster to Project Afterlife.',
            'ip_address' => '127.0.0.1',
            'confirmed_at' => now()->subHours(5),
        ]);

        // -------------------------------------------------------------
        // PROJECT 8: REVISION_REQUIRED
        // Owner: Kaito -> REVISION_REQUIRED
        // -------------------------------------------------------------
        $p8 = Project::updateOrCreate(
            ['slug' => 'retro-pixel-engine'],
            [
                'owner_id' => $kaito->id,
                'original_owner_id' => $kaito->id,
                'category_id' => $catLib->id,
                'title' => 'RetroPixel: 8-Bit Software Renderer & Audio Engine',
                'short_description' => 'Pure software rasterizer and chiptune synthesizer in C++ and WebAssembly.',
                'description' => "RetroPixel is an experimental 2D rendering pipeline capable of simulating retro CRT scanlines and synthesized sound chips.\n\nRequires updated README and database installation instructions as requested by administrator.",
                'project_type' => ProjectType::LIBRARY,
                'development_status' => DevelopmentStatus::CONCEPT,
                'reason_for_abandonment' => 'Lost interest in retro hardware emulation.',
                'original_development_date' => '2023-09-15',
                'last_development_date' => '2024-02-28',
                'status' => ProjectStatus::REVISION_REQUIRED,
                'revision_instructions' => 'Please provide a comprehensive README explaining build dependencies (Emscripten / CMake) and clarify the open-source license.',
                'is_featured' => false,
                'last_activity_at' => now()->subDays(3),
            ]
        );


        // -------------------------------------------------------------
        // PROJECT 9: UNDER RECOVERY (GoStream for Elena)
        // Original Owner: Amira -> Transferred to: Elena -> UNDER_RECOVERY
        // -------------------------------------------------------------
        $p9 = Project::updateOrCreate(
            ['slug' => 'gostream-distributed-transcoder'],
            [
                'owner_id' => $elena->id,
                'original_owner_id' => $amira->id,
                'category_id' => $catCli->id,
                'title' => 'GoStream: Distributed Video Transcoding Node',
                'short_description' => 'A resilient, multi-threaded FFmpeg job orchestrator in Go with Redis queue management.',
                'description' => "GoStream distributes high-load media encoding jobs across worker pools.\n\nElena adopted this repository to add HLS segmenting and Webhook notifications.",
                'project_type' => ProjectType::CLI,
                'development_status' => DevelopmentStatus::BETA,
                'reason_for_abandonment' => 'Original team shifted focus to a cloud SaaS product.',
                'original_development_date' => '2023-10-01',
                'last_development_date' => '2024-04-12',
                'status' => ProjectStatus::UNDER_RECOVERY,
                'is_featured' => true,
                'published_at' => now()->subMonths(2),
                'last_activity_at' => now()->subDays(1),
            ]
        );
        $p9->technologies()->sync([$techGo->id, $techDocker->id]);

        $tasks9 = [
            ['title' => 'Upgrade FFmpeg 6 bindings in Go', 'phase' => TaskPhase::REPAIR, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Implement distributed Redis BullMQ worker queue', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::URGENT, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Add adaptive bitrate HLS chunk generator', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::IN_PROGRESS],
            ['title' => 'Write benchmark stress tests under heavy CPU load', 'phase' => TaskPhase::TESTING, 'prio' => TaskPriority::MEDIUM, 'status' => TaskStatus::TODO],
            ['title' => 'Package multi-arch Docker image with hardware acceleration', 'phase' => TaskPhase::DEPLOYMENT, 'prio' => TaskPriority::MEDIUM, 'status' => TaskStatus::TODO],
        ];
        foreach ($tasks9 as $idx => $t) {
            RecoveryTask::create([
                'project_id' => $p9->id,
                'assigned_to' => $elena->id,
                'title' => $t['title'],
                'phase' => $t['phase'],
                'priority' => $t['prio'],
                'status' => $t['status'],
                'due_date' => now()->addWeeks(3),
                'completed_at' => $t['status'] === TaskStatus::COMPLETED ? now()->subDays(3) : null,
                'order_index' => $idx + 1,
            ]);
        }

        // -------------------------------------------------------------
        // PROJECT 10: UNDER RECOVERY (DevShield WAF for Marcus)
        // Original Owner: Devon -> Transferred to: Marcus -> UNDER_RECOVERY
        // -------------------------------------------------------------
        $p10 = Project::updateOrCreate(
            ['slug' => 'devshield-waf-proxy'],
            [
                'owner_id' => $marcus->id,
                'original_owner_id' => $devon->id,
                'category_id' => $catSec->id,
                'title' => 'DevShield: Reverse Proxy & WAF Middleware',
                'short_description' => 'Lightweight reverse proxy with rate limiting, OWASP rule filtering, and SQLi protection in TypeScript.',
                'description' => "DevShield intercepts malicious payloads before reaching internal Node.js/PHP backend APIs.\n\nMarcus adopted this project to add Redis rate limiting and Prometheus metrics.",
                'project_type' => ProjectType::API,
                'development_status' => DevelopmentStatus::ALPHA,
                'reason_for_abandonment' => 'Abandoned due to lack of time to implement TLS automated cert renewal.',
                'original_development_date' => '2024-01-05',
                'last_development_date' => '2024-06-15',
                'status' => ProjectStatus::UNDER_RECOVERY,
                'is_featured' => true,
                'published_at' => now()->subMonths(1),
                'last_activity_at' => now()->subHours(12),
            ]
        );
        $p10->technologies()->sync([$techTs->id, $techDocker->id]);

        $tasks10 = [
            ['title' => 'Audit OWASP Core Rule Set parser in TypeScript', 'phase' => TaskPhase::ASSESSMENT, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Implement sliding-window Redis rate limiter', 'phase' => TaskPhase::REPAIR, 'prio' => TaskPriority::HIGH, 'status' => TaskStatus::COMPLETED],
            ['title' => 'Add automated Let\'s Encrypt ACME certificate manager', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::URGENT, 'status' => TaskStatus::IN_PROGRESS],
            ['title' => 'Export Prometheus metrics endpoint for request latency and blocked IP stats', 'phase' => TaskPhase::DEVELOPMENT, 'prio' => TaskPriority::MEDIUM, 'status' => TaskStatus::TODO],
        ];
        foreach ($tasks10 as $idx => $t) {
            RecoveryTask::create([
                'project_id' => $p10->id,
                'assigned_to' => $marcus->id,
                'title' => $t['title'],
                'phase' => $t['phase'],
                'priority' => $t['prio'],
                'status' => $t['status'],
                'due_date' => now()->addWeeks(2),
                'completed_at' => $t['status'] === TaskStatus::COMPLETED ? now()->subDays(2) : null,
                'order_index' => $idx + 1,
            ]);
        }

        // Audit Logs Seed
        AuditLog::create(['user_id' => $admin->id, 'action' => 'SYSTEM_INIT', 'entity_type' => null, 'entity_id' => null, 'ip_address' => '127.0.0.1', 'user_agent' => 'ProjectAfterlifeSeeder/1.0', 'metadata' => ['note' => 'System seeded with verified development records']]);
    }
}
