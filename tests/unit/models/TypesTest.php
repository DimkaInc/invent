<?php

namespace tests\unit\models;

use app\models\Types;

class TypesTest extends \Codeception\Test\Unit
{
    private $types;
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->types = new Types();
    }

    protected function _after()
    {
    }

    public function testEnterNull()
    {
        // Пустое значение недопустимо
        $this->types->name = NULL;
        $this->assertFalse($this->types->validate([ 'name' ]));
    }

    public function testEnterAbove100()
    {
        // Больше 100 символов недопустимо
        $this->types->name = '**** ' . str_repeat('a', 100) . ' ****';
        $this->assertFalse($this->types->validate([ 'name' ]));
    }

    // tests
    public function testEnterData()
    {
        $validName = '--TEST TYPE--';
        // Допустимая комбинация
        $this->types->name = $validName;
        $this->assertTrue($this->types->validate([ 'name' ]));

        // Сохранение данных в базу
        $this->assertTrue($this->types->save());
        $count = count(Types::find()->where([ 'name' => $validName ])->all());
        $this->assertGreaterThan(0, $count);
        $this->assertEquals(1, $count);
#        $this->tester->seeInDatabase('types', [ 'name' => $validName ]); // ищет в реальной базе данных, а не в тестовой.
    }
}