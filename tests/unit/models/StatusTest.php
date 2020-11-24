<?php

namespace tests\unit\models;

use app\models\Status;

class StatusTest extends \Codeception\Test\Unit
{
    private $model;
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

    // tests
    public function testEnterNull()
    {
        $this->model = new Status();
    
        // Пустое значение недопустимо
        $this->model->name = NULL;
        $this->assertFalse($this->model->validate([ 'name' ]));
    }

    public function testEnterLong()
    {
        $this->model = new Status();

        // Больше 100 символов недопустимо
        $this->model->name = '**** aaaaabbbbbcccccdddddeeeeefffffggggghhhhhiiiiijjjjjkkkkklllllmmmmmnnnnnooooopppppqqqqqrrrrrsssss ****';
        $this->assertFalse($this->model->validate([ 'name' ]));
    }

    public function testEnterData()
    {
        $this->model = new Status();

        $validName = '--TEST STATUS--';
        // Допустимая комбинация
        $this->model->name = $validName;
        $this->assertTrue($this->model->validate([ 'name' ]));

        // Сохранение данных в базу
        $this->assertTrue($this->model->save());
        $count = count(Status::find()->where([ 'name' => $validName ])->all());
        $this->assertGreaterThan(0, $count);
        $this->assertEquals(1, $count);
    }
}