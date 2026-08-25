<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
        };
    }

    public function badgeClasses(): string
    {
        return match($this) {
            self::TODO => 'bg-slate-100 text-slate-700 ring-1 ring-slate-500/20',
            self::IN_PROGRESS => 'bg-sky-50 text-sky-700 ring-1 ring-sky-600/20',
            self::COMPLETED => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
        };
    }
}
