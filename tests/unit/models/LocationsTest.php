<?php

namespace tests\unit\models;

use app\models\Regions;
use app\models\Locations;

class LocationsTest extends \Codeception\Test\Unit
{
    private $model;
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->model = new Locations();
        $region = new Regions();
        $region->name = '-- TEST REGION FOR LOCATION --';
        $this->assertTrue($region->save());
        $this->model->region_id = $region->id;
    }

    protected function _after()
    {
    }

    // tests
    public function testEnterNull()
    {
        // Пустое значение недопустимо
        $this->model->name = NULL;
        $this->assertFalse($this->model->validate([ 'name' ]));
    }

    public function testEnterAbove120()
    {
        // Больше 120 символов недопустимо
        $this->model->name = '**** ' . str_repeat('a', 120) . ' ****';
        $this->assertFalse($this->model->validate([ 'name' ]));
    }

    public function testEnterData()
    {
        $validName = '--TEST LOCATION--';
        // Допустимая комбинация
        $this->model->name = $validName;
        $this->assertTrue($this->model->validate([ 'name' ]));

        // Сохранение данных в базу
        $this->assertTrue($this->model->save());
        $count = count(Locations::find()->where([ 'name' => $validName ])->all());
        $this->assertGreaterThan(0, $count);
        $this->assertEquals(1, $count);
    }
}