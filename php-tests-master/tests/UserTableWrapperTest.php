<?php
ini_set('memory_limit', '500M');
require_once '../src/UserTableWrapper.php';

use PHPUnit\Framework\TestCase;

class UserTableWrapperTest extends TestCase
{
    /**
     * @covers       UserTableWrapper::insert
     * @uses         UserTableWrapper::get
     * @dataProvider insertProvider
     */
    public function testInsert(array $values, array $expected): void
    {
        $userTable = new UserTableWrapper();
        $userTable->insert($values);
        $inserted = $userTable->get()[0];
        $this->assertEquals($expected, $inserted);
    }

    public function insertProvider(): array
    {
        return [
            [
                ['name' => 'Иван', 'surname' => 'Петров'], ['name' => 'Иван', 'surname' => 'Петров'],
            ],
        ];
    }

    /**
     * @covers       UserTableWrapper::update
     * @uses         UserTableWrapper::insert
     * @uses         UserTableWrapper::get
     * @dataProvider updateProvider
     */
    public function testUpdate(array $initial, int $id, array $modification, array $expected): void
    {
        $db = new UserTableWrapper();
        $db->insert($initial);
        $db->update($id, $modification);
        $modified = $db->get()[$id];
        $this->assertEquals($expected, $modified);
    }

    public function updateProvider(): array
    {
        return [
            [
                ['name' => 'Иван', 'surname' => 'Дурак'],
                0,
                ['name' => 'Иван', 'surname' => 'Царевич'],
                ['name' => 'Иван', 'surname' => 'Царевич']
            ],
        ];
    }

    /**
     * @covers       UserTableWrapper::delete
     * @uses         UserTableWrapper::get
     * @uses         UserTableWrapper::insert
     * @dataProvider deleteProvider
     */
    public function testDelete(array $values): void
    {
        $db = new UserTableWrapper();
        $db->insert($values);
        $db->delete(0);
        $rows = $db->get();
        $this->assertEquals(false, isset($rows[0]));
    }

    public function deleteProvider(): array
    {
        return [
            [['name' => 'Петр', 'surname' => 'Первый']],
        ];
    }

    /**
     * @covers       UserTableWrapper::get
     * @uses         UserTableWrapper::insert
     * @dataProvider getProvider
     */
    public function testGet(array $rows, array $expected): void
    {
        $db = new UserTableWrapper();
        foreach ($rows as $value) {
            $db->insert($value);
        }
        $insertedRows = $db->get();
        $this->assertEquals($expected, $insertedRows);
    }


    public function getProvider(): array
    {
        return
            [
                [
                    [
                        ['name' => 'Иван', 'surname' => 'первый'],
                        ['name' => 'Иван', 'surname' => 'второй'],
                        ['name' => 'Иван', 'surname' => 'третий'],
                    ],
                    [
                        ['name' => 'Иван', 'surname' => 'первый'],
                        ['name' => 'Иван', 'surname' => 'второй'],
                        ['name' => 'Иван', 'surname' => 'третий'],
                    ],
                ],
            ];
    }

}