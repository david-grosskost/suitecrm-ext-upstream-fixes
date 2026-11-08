<?php

declare(strict_types=1);

namespace SuiteCrmExt\UpstreamFixes\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SuiteCrmExt\UpstreamFixes\EmptyEntryPoint;

final class EmptyEntryPointTest extends TestCase
{
    /**
     * @return iterable<string, array{array<string, mixed>, array<string, mixed>}>
     */
    public static function requests(): iterable
    {
        $form = ['module' => 'Users', 'action' => 'ChangePassword'];

        yield 'entryPoint vide : retiré' => [[...$form, 'entryPoint' => ''], $form];
        yield 'entryPoint fait d\'espaces : retiré' => [[...$form, 'entryPoint' => ' '], $form];
        yield 'vrai entryPoint : gardé' => [
            ['entryPoint' => 'Changenewpassword', 'guid' => 'x'],
            ['entryPoint' => 'Changenewpassword', 'guid' => 'x'],
        ];
        yield 'sans entryPoint : inchangé' => [$form, $form];
        yield 'entryPoint en tableau : inchangé' => [['entryPoint' => ['']], ['entryPoint' => ['']]];
    }

    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $expected
     */
    #[DataProvider('requests')]
    public function testClean(array $request, array $expected): void
    {
        self::assertSame($expected, EmptyEntryPoint::clean($request));
    }

    public function testRemoveCleansEverySuperglobal(): void
    {
        $_GET = ['entryPoint' => ''];
        $_POST = ['entryPoint' => '', 'action' => 'ChangePassword'];
        $_REQUEST = ['entryPoint' => '', 'action' => 'ChangePassword'];

        // As SuiteCRM calls it: no arguments (null).
        (new EmptyEntryPoint())->remove('after_entry_point', null);

        self::assertSame([], $_GET);
        self::assertSame(['action' => 'ChangePassword'], $_POST);
        self::assertSame(['action' => 'ChangePassword'], $_REQUEST);
    }
}
