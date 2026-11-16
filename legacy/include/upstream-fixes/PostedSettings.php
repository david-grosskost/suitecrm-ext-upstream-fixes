<?php

declare(strict_types=1);

namespace SuiteCrmExt\UpstreamFixes;

/**
 * SuiteCRM 8 bug: the administration settings kept in the config table
 * (System Name of Administration → System Settings, and the other "system_",
 * "notify_", "proxy_"… settings) are never saved. Their forms post them, but
 * Administration::saveConfig() reads only $_GET since SuiteCRM 8 (SuiteCRM 7
 * read $_POST, same line). The posted settings are copied into $_GET before
 * SuiteCRM handles the request; nothing else changes.
 *
 * Limited to what it fixes: a POST to a module whose settings pages call
 * Administration::saveConfig(), and only the settings of its categories that
 * the address does not already carry. Who may save stays SuiteCRM's check, in
 * those pages (administrators only): the user is not known yet when this hook
 * runs, and the fix only puts back what SuiteCRM 7 read.
 *
 * Remove this fix once a SuiteCRM version reads $_POST again.
 */
final class PostedSettings
{
    /**
     * Administration::$config_categories of SuiteCRM 8.10 ("mail" is no
     * longer one: outbound email has its own settings).
     */
    public const array CATEGORIES = ['disclosure', 'notify', 'system', 'portal', 'proxy', 'massemailer', 'ldap', 'captcha', 'sugarpdf'];

    /** Modules whose settings pages save through Administration::saveConfig(). */
    public const array MODULES = ['Configurator', 'Administration', 'EmailMan', 'Campaigns'];

    /**
     * after_entry_point logic hook (application-wide, so without a bean): runs
     * once SuiteCRM is loaded, before it handles the request. SuiteCRM calls it
     * with null arguments (LogicHook::call_custom_logic), hence mixed.
     */
    public function copy(string $event, mixed $arguments = null): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? '';

        $_GET = self::withPosted($_GET, $_POST, is_string($method) ? $method : '');
    }

    /**
     * $_GET with the posted settings added, when the request is one this fix
     * is for; unchanged otherwise.
     *
     * @param array<mixed> $get
     * @param array<mixed> $post
     *
     * @return array<mixed>
     */
    public static function withPosted(array $get, array $post, string $method): array
    {
        $module = $post['module'] ?? $get['module'] ?? null;
        if (strtoupper($method) !== 'POST' || !in_array($module, self::MODULES, true)) {
            return $get;
        }

        foreach ($post as $key => $value) {
            if (is_string($key) && !array_key_exists($key, $get) && self::isSetting($key)) {
                $get[$key] = $value;
            }
        }

        return $get;
    }

    /** A setting of the config table: "<category>_<name>", as Administration::get_config_prefix() reads it. */
    private static function isSetting(string $key): bool
    {
        $separator = strpos($key, '_');

        return $separator !== false && $separator > 0
            && in_array(substr($key, 0, $separator), self::CATEGORIES, true);
    }
}
