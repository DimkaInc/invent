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

    }

    protected function _after()
    {
    }

    public function testEnterNull()
    {
        $this->types = new Types();
    
        // Пустое значение недопустимо
        $this->types->name = NULL;
        $this->assertFalse($this->types->validate([ 'name' ]));
    }
    
    public function testEnterLong()
    {
        $this->types = new Types();
    
        // Больше 100 символов недопустимо
        $this->types->name = '**** aaaaabbbbbcccccdddddeeeeefffffggggghhhhhiiiiijjjjjkkkkklllllmmmmmnnnnnooooopppppqqqqqrrrrrsssss ****';
        $this->assertFalse($this->types->validate([ 'name' ]));
    }

    // tests
    public function testEnterData()
    {
        $this->types = new Types();

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