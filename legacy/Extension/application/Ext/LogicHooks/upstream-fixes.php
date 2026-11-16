<?php

// Correctifs de SuiteCRM (extension upstream-fixes): application-wide hooks,
// merged by Quick Repair into custom/application/Ext/LogicHooks/.

$hook_version = 1;

// Password change at the first login: see EmptyEntryPoint.
/** @var array<string, list<array{int, string, string, string, string}>> $hook_array Set by SuiteCRM */
$hook_array['after_entry_point'][] = [
    10,
    'upstream-fixes : entryPoint vide retiré de la requête',
    'custom/include/upstream-fixes/EmptyEntryPoint.php',
    'SuiteCrmExt\UpstreamFixes\EmptyEntryPoint',
    'remove',
];

// Administration settings never saved by SuiteCRM 8: see PostedSettings.
$hook_array['after_entry_point'][] = [
    20,
    'upstream-fixes : réglages d\'administration postés recopiés là où SuiteCRM les lit',
    'custom/include/upstream-fixes/PostedSettings.php',
    'SuiteCrmExt\UpstreamFixes\PostedSettings',
    'copy',
];
