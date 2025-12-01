<?php

return [
    /**
     * The role ID or name to revert users to when their subscription expires.
     * If null, the system will look for a role named 'listener' by default.
     */
    'expired_role_id' => null,

    /**
     * Default subscription duration in days (used if plan doesn't specify).
     * Can be overridden per-plan in the plans table.
     */
    'default_duration_days' => 30,
];
