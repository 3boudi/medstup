<?php

namespace App\Enums;

enum NotificationType: string
{
    case NEW_MESSAGE           = 'new_message';
    case SEND_IMAGE            = 'send_image';
    case SEND_FILE             = 'send_file';

    case REQUEST_SENT          = 'consultation_requested';
    case REQUEST_ACCEPTED      = 'consultation_accepted';
    case REQUEST_REJECTED      = 'consultation_rejected';
    case REQUEST_CANCELED      = 'consultation_canceled';

    case DOCTOR_REGISTERED     = 'doctor_registered';
    case DOCTOR_APPROVED       = 'doctor_approved';
    case DOCTOR_REJECTED       = 'doctor_rejected';

    case CHAT_CLOSED           = 'chat_closed';
    case NEW_NOTIFICATION      = 'new_notification';

    case SYSTEM_ANNOUNCEMENT   = 'system_announcement'; // مثل إشعارات عامة من الإدمن
}

