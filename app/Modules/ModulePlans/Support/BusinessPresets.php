<?php

declare(strict_types=1);

namespace App\Modules\ModulePlans\Support;

final class BusinessPresets
{
    /**
     * @return array<string, array{label: string, modules: list<string>}>
     */
    public static function all(): array
    {
        $cores = [
            'tenancy',
            'users_security',
            'module_plans',
            'audit',
            'notifications',
        ];

        return [
            'small_guarding' => [
                'label' => 'Small guarding company',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'shift_scheduling',
                    'attendance',
                    'patrol_routes',
                    'patrol_execution',
                    'incidents',
                    'dar',
                ])),
            ],
            'residential_society' => [
                'label' => 'Residential society',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'visitors',
                    'vehicles_gate',
                    'hazards',
                    'patrol_execution',
                    'sos',
                ])),
            ],
            'corporate_campus' => [
                'label' => 'Corporate campus',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'visitors',
                    'key_control',
                    'assets',
                    'inspections',
                ])),
            ],
            'mall_retail' => [
                'label' => 'Mall / retail',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'patrol_routes',
                    'patrol_execution',
                    'incidents',
                    'hazards',
                    'tasks_post_orders',
                    'alarms',
                ])),
            ],
            'industrial' => [
                'label' => 'Industrial site',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'vehicles_gate',
                    'hazards',
                    'emergency',
                    'assets',
                ])),
            ],
            'event_security' => [
                'label' => 'Event security',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'shift_scheduling',
                    'incidents',
                    'control_room',
                ])),
            ],
            'cash_in_transit' => [
                'label' => 'Cash in transit',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'live_tracking',
                    'sos',
                    'assets',
                ])),
            ],
            'remote_monitoring' => [
                'label' => 'Remote monitoring',
                'modules' => array_values(array_unique([
                    ...$cores,
                    'sos',
                    'alarms',
                    'control_room',
                ])),
            ],
            'enterprise' => [
                'label' => 'Enterprise (everything synced)',
                'modules' => ['*'],
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function keys(): array
    {
        return array_keys(self::all());
    }
}
