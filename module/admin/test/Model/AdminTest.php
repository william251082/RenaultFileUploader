<?php
namespace AdimnTest\Model;

use Adimn\Model\Adimn;
use PHPUnit_Framework_TestCase as TestCase;

class AdimnTest extends TestCase
{
    public function testInitialAdimnValuesAreNull()
    {
        $adimn = new Adimn();

        $this->assertNull($adimn->artist, '"artist" should be null by default');
        $this->assertNull($adimn->id, '"id" should be null by default');
        $this->assertNull($adimn->title, '"title" should be null by default');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $adimn = new Adimn();
        $data  = [
            'artist' => 'some artist',
            'id'     => 123,
            'title'  => 'some title'
        ];

        $adimn->exchangeArray($data);

        $this->assertSame(
            $data['artist'],
            $adimn->artist,
            '"artist" was not set correctly'
        );

        $this->assertSame(
            $data['id'],
            $adimn->id,
            '"id" was not set correctly'
        );

        $this->assertSame(
            $data['title'],
            $adimn->title,
            '"title" was not set correctly'
        );
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $admin = new Admin();

        $admin->exchangeArray([
            'artist' => 'some artist',
            'id'     => 123,
            'title'  => 'some title',
        ]);
        $admin->exchangeArray([]);

        $this->assertNull($admin->artist, '"artist" should default to null');
        $this->assertNull($admin->id, '"id" should default to null');
        $this->assertNull($admin->title, '"title" should default to null');
    }

    public function testGetArrayCopyReturnsAnArrayWithPropertyValues()
    {
        $admin = new Admin();
        $data  = [
            'artist' => 'some artist',
            'id'     => 123,
            'title'  => 'some title'
        ];

        $admin->exchangeArray($data);
        $copyArray = $admin->getArrayCopy();

        $this->assertSame($data['artist'], $copyArray['artist'], '"artist" was not set correctly');
        $this->assertSame($data['id'], $copyArray['id'], '"id" was not set correctly');
        $this->assertSame($data['title'], $copyArray['title'], '"title" was not set correctly');
    }

    public function testInputFiltersAreSetCorrectly()
    {
        $admin = new Admin();

        $inputFilter = $admin->getInputFilter();

        $this->assertSame(3, $inputFilter->count());
        $this->assertTrue($inputFilter->has('artist'));
        $this->assertTrue($inputFilter->has('id'));
        $this->assertTrue($inputFilter->has('title'));
    }
}