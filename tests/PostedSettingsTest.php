<?php

declare(strict_types=1);

namespace SuiteCrmExt\UpstreamFixes\Tests;

use PHPUnit\Framework\TestCase;
use SuiteCrmExt\UpstreamFixes\PostedSettings;

/** The administration settings posted by their forms, copied where SuiteCRM 8 reads them. */
final class PostedSettingsTest extends TestCase
{
    private const array GET = ['module' => 'Configurator', 'action' => 'SaveConfig'];

    private const array POST = [
        'module' => 'Configurator',
        'action' => 'SaveConfig',
        'system_name' => 'CRM Dupont',
        'list_max_entries_per_page' => '20',
        'proxy_on' => '0',
    ];

    public function testTheSystemNameReachesWhereSuiteCrmReadsIt(): void
    {
        $get = PostedSettings::withPosted(self::GET, self::POST, 'POST');

        self::assertSame('CRM Dupont', $get['system_name'] ?? null);
        self::assertSame('0', $get['proxy_on'] ?? null);
    }

    public function testSettingsOutsideTheConfigTableAreLeft(): void
    {
        $get = PostedSettings::withPosted(self::GET, self::POST, 'POST');

        // Saved by the Configurator into config_override.php, from $_POST.
        self::assertArrayNotHasKey('list_max_entries_per_page', $get);
    }

    public function testTheAddressWins(): void
    {
        $get = PostedSettings::withPosted([...self::GET, 'system_name' => 'From the address'], self::POST, 'POST');

        self::assertSame('From the address', $get['system_name']);
    }

    public function testNotAPost(): void
    {
        self::assertSame(self::GET, PostedSettings::withPosted(self::GET, self::POST, 'GET'));
    }

    public function testAnotherModule(): void
    {
        $post = [...self::POST, 'module' => 'Accounts'];

        self::assertSame(['module' => 'Accounts'], PostedSettings::withPosted(['module' => 'Accounts'], $post, 'POST'));
    }

    public function testAKeyThatOnlyLooksLikeASetting(): void
    {
        $post = [...self::POST, '_system' => 'x', 'systemname' => 'x', 'system' => 'x'];
        $get = PostedSettings::withPosted(self::GET, $post, 'POST');

        self::assertArrayNotHasKey('_system', $get);
        self::assertArrayNotHasKey('systemname', $get);
        self::assertArrayNotHasKey('system', $get);
    }

    public function testCopyRunsOnTheSuperglobals(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET = self::GET;
        $_POST = self::POST;

        // As SuiteCRM calls it: no arguments (null).
        (new PostedSettings())->copy('after_entry_point', null);

        self::assertSame('CRM Dupont', $_GET['system_name'] ?? null);
        self::assertSame(self::POST, $_POST, 'the posted values are left as they are');
    }
}
