<?php

declare(strict_types=1);

namespace SuiteCrmExt\UpstreamFixes;

/**
 * SuiteCRM bug: the password change asked at the first login (and after a
 * reset) cannot be saved. Its form (modules/Users/Changenewpassword.tpl)
 * always sends an "entryPoint" field, empty outside the "forgot password"
 * link; SuiteCRM then refuses the request ("Aucune action avec ce nom",
 * SugarController::handleEntryPoint). An empty entryPoint means "no entry
 * point": it is removed from the request before SuiteCRM reads it.
 *
 * Remove this fix once a SuiteCRM version no longer sends an empty field.
 */
final class EmptyEntryPoint
{
    /**
     * after_entry_point logic hook (application-wide, so without a bean): runs
     * once SuiteCRM is loaded, before it handles the request.
     *
     * @param array<mixed> $arguments
     */
    public function remove(string $event, array $arguments): void
    {
        $_GET = self::clean($_GET);
        $_POST = self::clean($_POST);
        $_REQUEST = self::clean($_REQUEST);
    }

    /**
     * The request parameters without an empty entryPoint; any other value
     * (a real entry point) is kept.
     *
     * @param array<mixed> $parameters
     *
     * @return array<mixed>
     */
    public static function clean(array $parameters): array
    {
        $entryPoint = $parameters['entryPoint'] ?? null;
        if (is_string($entryPoint) && trim($entryPoint) === '') {
            unset($parameters['entryPoint']);
        }

        return $parameters;
    }
}
