<?php

namespace App;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'inprogress';
    case Completed = 'completed';
    case Overdue = 'overdue';
    /**
     * Convert user input (any case) to a TaskStatus enum instance.
     */
    public static function fromUserInput(string $input): self
    {
        $input = strtolower($input);

        return match ($input) {
            'pending' => self::Pending,
            'inprogress' => self::InProgress,
            'completed' => self::Completed,
            'overdue' => self::Overdue,
            default => throw new \InvalidArgumentException("Invalid status: {$input}")
        };
    }
}
