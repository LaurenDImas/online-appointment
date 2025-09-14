<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case PendingApproval = 'pending_approval';
    case Upcoming = 'upcoming';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Canceled = 'canceled';
}
