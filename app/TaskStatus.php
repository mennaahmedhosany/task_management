<?php

namespace App;

enum TaskStatus: string
{
    case Pending = 'Pending';
    case InProgress = 'InProgress';
    case Completed = 'Completed';
    case Overdue = 'Overdue';
}
