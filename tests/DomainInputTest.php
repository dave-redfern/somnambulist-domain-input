<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace Somnambulist\Tests\Domain;

use PHPUnit\Framework\TestCase;
use Somnambulist\Collection\Collection;
use Somnambulist\Collection\Immutable;
use Somnambulist\Domain\DomainInput;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class DomainInputTest
 *
 * @package    Somnambulist\Tests\Domain
 * @subpackage Somnambulist\Tests\Domain\DomainInputTest
 */
class DomainInputTest extends TestCase
{

    public function testGetInput()
    {
        $input = new DomainInput(new Collection(['foo' => ['bar' => 'baz']]));

        $this->assertEquals('baz', $input->get('foo.bar'));
        $this->assertEquals('baz', $input->input('foo.bar'));
    }

    public function testGetInputReturnsDefault()
    {
        $input = new DomainInput(new Collection(['foo' => ['bar' => 'baz']]));

        $this->assertEquals('baz', $input->get('bar', 'baz'));
        $this->assertEquals('baz', $input->input('bar', 'baz'));
    }

    public function testGetInputReturnsWrappedCollection()
    {
        $input = new DomainInput(new Collection(['foo' => new Collection(['bar' => 'baz'])]));

        $this->assertInstanceOf(DomainInput::class, $input->get('foo'));
    }

    public function testHasInput()
    {
        $input = new DomainInput(new Collection(['foo' => ['bar' => 'baz']]));

        $this->assertTrue($input->has('foo'));
        $this->assertFalse($input->has('example'));
    }

    public function testGetFile()
    {
        $input = new DomainInput(new Collection(), new Collection([
            'file' => new UploadedFile(__FILE__, __FILE__, 'plain/text'),
        ]));

        $this->assertInstanceOf(UploadedFile::class, $input->file('file'));
    }

    public function testGetFileReturnsNullIfDoesNotExist()
    {
        $input = new DomainInput(new Collection([
            'file' => new UploadedFile(__FILE__, __FILE__, 'plain/text'),
        ]));

        $this->assertNull($input->file('bar'));
    }

    public function testPassedCollectionsAreConvertedToImmutable()
    {
        $input = new DomainInput(new Collection(), new Collection());

        $this->assertInstanceOf(Immutable::class, $input->inputs());
        $this->assertInstanceOf(Immutable::class, $input->files());
    }
}
