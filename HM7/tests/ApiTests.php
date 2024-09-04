<?php declare(strict_types=1);

namespace HM7\Tests;

use PHPUnit\Framework\TestCase;


final class ApiTests extends TestCase
{
    private string $filename;

    protected function setUp(): void
    {
        $this->filename = __DIR__ . '/test_teachers.json';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->filename)) {
            unlink($this->filename);
        }
    }

    public function testSaveTeachersToFile(): void
    {
        $teachers = [
            ['id' => '1', 'name' => 'John Doe', 'subject' => 'Mathematics', 'email' => 'Bai@Ivan.com'],
            ['id' => '2', 'name' => 'Pesho', 'subject' => 'History', 'email' => 'Pesho@abv.bg']
        ];

        $this->saveTeachersToFile($teachers);

        $this->assertFileExists($this->filename);

        $content = file_get_contents($this->filename);
        $loadedTeachers = json_decode($content, true);

        $this->assertIsArray($loadedTeachers);
        $this->assertCount(2, $loadedTeachers);
        $this->assertEquals($teachers, $loadedTeachers);
    }

    public function testLoadTeachersFromFile(): void
    {
        $teachers = [
            ['id' => '1', 'name' => 'John Doe', 'subject' => 'Mathematics', 'email' => 'Bai@Ivan.com']
        ];

        file_put_contents($this->filename, json_encode($teachers, JSON_PRETTY_PRINT));

        $loadedTeachers = $this->loadTeachersFromFile();

        $this->assertIsArray($loadedTeachers);
        $this->assertCount(1, $loadedTeachers);
        $this->assertEquals($teachers[0], $loadedTeachers[0]);
    }

    public function testUpdateTeacherInFile(): void
    {
        $initialTeachers = [
            ['id' => '1', 'name' => 'John Doe', 'subject' => 'Mathematics', 'email' => 'Bai@Ivan.com']
        ];

        $updatedTeacher = [
            'id' => '1',
            'name' => 'John Doe Doe',
            'subject' => 'Maths',
            'email' => 'Bai@Ivan.com'
        ];

        file_put_contents($this->filename, json_encode($initialTeachers, JSON_PRETTY_PRINT));

        $teachers = $this->loadTeachersFromFile();
        foreach ($teachers as &$teacher) {
            if ($teacher['id'] === $updatedTeacher['id']) {
                $teacher = $updatedTeacher;
            }
        }

        $this->saveTeachersToFile($teachers);

        $updatedTeachers = $this->loadTeachersFromFile();
        $this->assertEquals($updatedTeacher, $updatedTeachers[0]);
    }

    private function loadTeachersFromFile(): array
    {
        if (!file_exists($this->filename)) {
            return [];
        }

        $json = file_get_contents($this->filename);
        return json_decode($json, true);
    }

    private function saveTeachersToFile(array $teachers): void
    {
        file_put_contents($this->filename, json_encode($teachers, JSON_PRETTY_PRINT));
    }
}
